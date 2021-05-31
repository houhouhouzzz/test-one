<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\WhatAppMultiClose;
use App\Admin\Actions\Post\WhatAppMultiOpen;
use App\Model\WhatApp;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Cache;

class WhatAppController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'What App';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WhatApp());
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('phone', 'what app');

            $filter->equal('status')->select(WhatApp::$status_maps);

        });


//        $grid->column('id', 'ID')->sortable();
        $grid->column('phone', __('what app'))->editable();
        $grid->column('status', '状态')->radio(WhatApp::$status_maps);
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

        $grid->batchActions(function ($actions){
            $actions->add(new WhatAppMultiOpen());
        });
        $grid->batchActions(function ($actions){
            $actions->add(new WhatAppMultiClose());
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
        $show = new Show(WhatApp::findOrFail($id));

//        $show->field('id', __('Id'));
        $show->field('phone', __('what app'));
        $show->status()->using(WhatApp::$status_maps);
        $show->field('created_at', __('admin.created_at'));
        $show->field('updated_at', __('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WhatApp());

        $form->text('phone', __('what app'));
        $form->select('status', '状态')->options( WhatApp::$status_maps);

        $form->saved(function(){
            Cache::forget(WhatApp::ALL_WHAT_APPS);
        });

        return $form;
    }
}
