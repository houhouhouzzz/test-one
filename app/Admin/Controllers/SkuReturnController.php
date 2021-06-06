<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\SkuReturn\ImportSkuReturn;
use App\Admin\Actions\Post\SkuReturn\ExportSkuReturnTemplate;
use App\Extensions\Util;
use App\Model\Order;
use App\Model\ShippingMethod;
use App\Model\Sku;
use App\Model\SkuReturn;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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

        $grid->expandFilter();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableCreateButton();
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

        $form->number('sku_id', __('Sku id'));
        $form->number('country_code', __('Country code'));
        $form->number('shipping_method_id', __('Shipping method id'));
        $form->text('origin_tracking_number', __('Origin tracking number'));

        return $form;
    }
}
