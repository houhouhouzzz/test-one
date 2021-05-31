<?php

namespace App\Admin\Controllers;

use App\Extensions\Util;
use App\Model\Warehouse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Model\SkuInventory;

class SkuInventoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SkuInventory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SkuInventory());

        $grid->expandFilter();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableExport();

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(1/2, function ($filter) {
                $filter->equal('warehouse_id', '仓库')->select(array_column(Warehouse::getCacheValidAll(), 'name', 'id'));
            });

            $filter->column(1/2, function ($filter) {
                $filter->like('sku.sku', 'sku');
            });

        });

        $grid->column('id', __('Id'));
        $grid->column('warehouse_id', __('仓库'))->display(function($warehouse_id){
            return array_column(Warehouse::getCacheValidAll(), 'name', 'id')[$warehouse_id]??'';
        });
        $grid->column('sku.sku', __('sku'));
        $grid->column('sku.image', 'sku图片')->display(function($image){
            if(!empty($image)){
                return Util::to($image);
            }
            return '';
        })->image();
        $grid->column('inventory', __('库存'))->display(function($inventory){
            if($inventory < 5){
                return  "<span style='color: red'>$inventory</span>";
            }

            if($inventory > 10){
                return  "<span style='color: green'>$inventory</span>";
            }


            return $inventory;
        });

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
        $show = new Show(SkuInventory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('warehouse_id', __('Warehouse id'));
        $show->field('sku_id', __('Sku id'));
        $show->field('inventory', __('Inventory'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SkuInventory());

        $form->number('warehouse_id', __('Warehouse id'));
        $form->number('sku_id', __('Sku id'));
        $form->switch('inventory', __('Inventory'));

        return $form;
    }
}
