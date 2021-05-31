<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Model\Warehouse;
use Illuminate\Support\Facades\Cache;

class WarehouseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '仓库';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Warehouse());
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->disableExport();

        $grid->actions(function($tool){
            $tool->disableDelete();
            $tool->disableView();
        });



        $grid->column('id', __('Id'));
        $grid->column('name', __('仓库名'));
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
        $show = new Show(Warehouse::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __(''));
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
        $form = new Form(new Warehouse());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
            $tools->disableList();
        });

        $form->text('name', __('仓库名'));

        $form->saved(function(){
            Cache::forget(Warehouse::ALL_WAREHOUSE);
        });

        return $form;
    }
}
