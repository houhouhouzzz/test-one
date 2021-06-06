<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\BatchReceive;
use App\Extensions\Util;
use App\Model\Sku;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Model\Purchase;

class PurchaseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '采购单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Purchase());

        $grid->model()->where('status', '<', Purchase::STATUS_DELETE);

        $grid->disableExport();

        $grid->actions(function($tool){
            $tool->disableView();
        });

        $grid->batchActions(function ($batch) {
            $batch->add(new BatchReceive());
        });

        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('sku_id', __('Sku'))->display(function($sku_id){
            $display = '';
            $sku = Sku::find($sku_id);
            if($sku){
                $display = $sku->sku;
            }
            return $display;
        });
        $grid->column('status', __('状态'))->display(function ($status){
            return Purchase::STATUS_MAP[$status];
        });
        $grid->column('quantity', __('数量'));
        $grid->column('total', __('采购价格'));
        $grid->column('image_url', '图片')->display(function($image_url){
            if(!empty($image_url)){
                return Util::to( $image_url);
            }
            return '';
        })->image();
        $grid->column('third_purchase_number', __('第三方采购单号'));
        $grid->column('tracking_company', __('物流公司'));
        $grid->column('tracking_number', __('物流单号'))->display(function ($tracking_number){
            return sprintf('<a target="_blank" href="https://www.kuaidi100.com/?nu=%s">%s</a>', $tracking_number,$tracking_number);
        });
        $grid->column('note', __('采购备注'));
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
        $show = new Show(Purchase::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('sku', __('Sku'));
        $show->field('status', __('Status'));
        $show->field('quantity', __('Quantity'));
        $show->field('total', __('Total'));
        $show->field('image_url', __('Image url'));
        $show->field('third_purchase_number', __('第三方采购单号'));
        $show->field('tracking_company', __('物流公司'));
        $show->field('tracking_number', __('物流单号'));
        $show->field('note', __('采购备注'));
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
        $form = new Form(new Purchase());

        $form->select('sku_id', __('Sku'))->rules('required', [
            'required' => 'sku必选',
        ])->options(function ($id) {
            $sku = Sku::find($id);
            if ($sku) {
                return [$sku->id => $sku->sku];
            }
        })->ajax('/admin/api/sku');
        $form->number('quantity', __('采购数量'))->value(1)->rules('required|min:1', [
            'required' => '标题必填',
            'min' => '最小值为1',
        ]);
//        $form->image('image_url', '采购凭证(图片)')->removable()->downloadable()->rules('required',[
//            'required' => '采购凭证(图片)必填',
//        ]);
        $form->decimal('total', __('采购价格'))->rules('required|min:0.01', [
            'required' => '采购价格必填',
            'min' => '最小值为0.01',
        ]);;
        $form->text('third_purchase_number', __('第三方采购单号'));
        $form->text('tracking_company', __('物流公司'));
        $form->text('tracking_number', __('物流单号'));
        $form->text('note', __('备注'));

        return $form;
    }
}
