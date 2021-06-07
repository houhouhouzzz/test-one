<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\Product\JumpProductUrl;
use App\Extensions\Util;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductSupplier;
use App\Model\Purchase;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Model\Sku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '欠货';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Sku());

        $grid->model()->where('status', Sku::STATUS_ONLINE)->whereIn('id',
            Order::join('order_products', 'order_products.order_id', '=', 'orders.id')
                ->where('orders.order_status', Order::ORDER_STATUS_CONFIRM)
                ->distinct('sku_id')->pluck('sku_id')
        );

        $grid->expandFilter();

        $grid->filter(function ($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->column(1/3, function ($filter) {
                $filter->like('sku', 'Sku');
            });
        });

        $grid->disableActions();

        $grid->disableCreateButton();
        $grid->disableRowSelector();

//        $grid->actions(function($tool){
//            $tool->disableView();
//            $tool->disableDelete();
//            $tool->disableEdit();
//        });

        $grid->column('sku', __('Sku'));
        $grid->column('image', '图片')->display(function($image_url){
            if(!empty($image_url)){
                return Util::to( $image_url);
            }
            return '';
        })->image();
        $grid->column('confirm_number', '确认订单数')->display(function (){
            return Order::join('order_products', 'order_products.order_id', '=', 'orders.id')
                ->where('orders.order_status', Order::ORDER_STATUS_CONFIRM)
                ->where('order_products.sku_id', $this->id)->count();
        });
        $grid->column('un_confirm_number', '72小时未确认订单数')->display(function (){
            $this->tmp_un_confirm_number =Order::join('order_products', 'order_products.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', Carbon::now()->subDays(3))
                ->where('orders.order_status', Order::ORDER_STATUS_UNCONFIRM)
                ->where('order_products.sku_id', $this->id)->count();
            return $this->tmp_un_confirm_number;
        });

        $grid->column('purchases', '已采购数量(在途中)')->display(function (){
            $purchases = [];
            $ps = Purchase::where('sku_id', $this->id)->where('status', Purchase::STATUS_PROCESSING)->get();
            foreach ($ps as $p){
                $purchases[] = sprintf( '1688采购单号:%s, 数量:%d', $p->third_purchase_number, $p->quantity);
            }
            return join(',', $purchases);
        });

        $grid->column('product.id', '采购信息')->display(function ($product_id){
            $supplier = ProductSupplier::where(compact('product_id'))->first();
            if($supplier){
                return sprintf('采购链接 : <a target="_blank" href="%s">%s</a> <br> 采购备注 ： %s',
                    $supplier->link, $supplier->link, $supplier->note);
            }
            return '';
        });

        $grid->column('to_purchase', '跳转采购单')->display(function (){
            return  sprintf('<a class="btn btn-sm btn-info" target="_blank" href="/admin/purchases/create?sku_id=%s&quantity=%s">创建采购单</a>', $this->id, $this->tmp_un_confirm_number);
        });

        return $grid;
    }
}
