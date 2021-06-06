<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\SkuReturn\ImportSkuReturn;
use App\Admin\Actions\Post\SkuReturn\ExportSkuReturnTemplate;
use App\Extensions\Util;
use App\Model\Order;
use App\Model\ShippingMethod;
use App\Model\Sku;
use App\Model\SkuInventory;
use App\Model\SkuReturn;
use App\Model\Warehouse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

class SkuReturnController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '已退件';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SkuReturn());

        $grid->model()->orderByDesc('created_at');

        $grid->expandFilter();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableColumnSelector();
        $grid->export(function ($export) {
            $export->column('country_code', function ($value, $original) {
                return Order::ORDER_COUNTRIES[$original]??'';
            });
        });

        $grid->filter(function ($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(1/3, function ($filter) {
                $filter->like('sku.sku', 'Sku');
            });

            $filter->column(1/3, function ($filter) {
                $filter->equal('country_code', '国家')->select(Order::ORDER_COUNTRIES);
            });

            $filter->column(1/3, function ($filter) {
                $filter->like('origin_tracking_number', '原物流单号');
            });
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ExportSkuReturnTemplate);

            $tools->append(new ImportSkuReturn);
        });

        $grid->column('sku_id', __('Sku'))->display(function ($sku_id){
            return Sku::find($sku_id)->sku;
        });
        $grid->column('country_code', __('国家'))->display(function ($country_code){
            $link = Util::to('return/' . $country_code);
            $value = Order::ORDER_COUNTRIES[$country_code]??'';
            $display = sprintf( '<a target="_blank" href="%s">%s</a>', $link, $value);
            return $display;
        });
        $grid->column('shipping_method_id', __('物流商'))->display(function($shipping_method_id){
            return ShippingMethod::SHIPPING_METHOD_MAP[$shipping_method_id]??'';
        });
        $grid->column('origin_tracking_number', __('原物流单号'));
        $grid->column('created_at', __('admin.created_at'));
        $grid->column('updated_at', __('admin.updated_at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(SkuReturn::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('sku_id', __('Sku id'));
        $show->field('country_code', __('Country code'));
        $show->field('shipping_method_id', __('Shipping method id'));
        $show->field('origin_tracking_number', __('Origin tracking number'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SkuReturn());

        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->disableEditingCheck();

        $form->text('origin_tracking_number', __('运单号'))->rules('required',[
            'required' => '运单号必填',
        ]);

        $form->saving(function (Form $form) {
            if(SkuReturn::where('origin_tracking_number', $form->origin_tracking_number)->first()){
                $error = new MessageBag([
                    'title'   => '该运单号已退,请核对',
                ]);
                return back()->with(compact('error'));
            }

            $order = Order::where(['tracking_number'=>$form->origin_tracking_number])->first();
            if(!$order){
                $error = new MessageBag([
                    'title'   => '该运单号不存在,请核对',
                ]);
                return back()->with(compact('error'));
            }
            foreach ($order->products as $product){
                $params = [
                    'sku_id' => $product->sku_id,
                    'origin_tracking_number' => $order->tracking_number,
                ];
                $sku_return = SkuReturn::where($params)->first();
                if(!$sku_return){
                    SkuReturn::create(array_merge($params, [
                        'shipping_method_id' => $order->shipping_method_id,
                        'country_code' => array_flip(Order::ORDER_COUNTRIES)[$order->country]??'',
                    ]));
                    SkuInventory::incrementInventory($product->sku_id, $product->quantity, Warehouse::DEFAULT_EXCHANGE_ID);
                }
            }
            $order->order_status = Order::ORDER_STATUS_REFUND;
            $order->save();

            admin_toastr('退件成功');
            return redirect('/admin/sku-returns');
        });

        return $form;
    }
}
