<?php

namespace App\Admin\Controllers;

use App\Model\TermService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Cache;

class TermServiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '服务条款';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TermService());

        $grid->column('name', __('条款名称'));
//        $grid->column('content', __('内容'));
        $grid->column('status', __('物流状态'))
            ->editable('select', TermService::STATUS_MAP);

        $grid->column('sort', __('排序'))
            ->editable('select', TermService::SORT_MAP);
//        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('更新时间'));

        $grid->setActionClass(Grid\Displayers\Actions::class);

        $grid->disablePagination();

        $grid->disableCreateButton();

        $grid->disableFilter();

        $grid->disableRowSelector();

        $grid->disableColumnSelector();

        $grid->disableTools();

        $grid->disableExport();

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableDelete();
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
        $show = new Show(TermService::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('content', __('Content'));
        $show->field('status', __('Status'));
        $show->field('sort', __('Sort'));
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
        $form = new Form(new TermService());

        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->text('name', __('条款名称'));
        $form->textarea('content', __('条款内容'));

        $form->saved(function(){
            Cache::forget(TermService::ALL_VALID_TERM_SERVICE);
        });

        return $form;
    }


    public function update($id){
        $post = request()->all();
        if(isset($post['value']) && !empty($post['name'])){
            $product = TermService::findOrFail($id);
            $product->{$post['name']} = $post['value'];
            $product->save();
            Cache::forget(TermService::ALL_VALID_TERM_SERVICE);
            return ['display'=> [], 'message'=> "更新成功 !", 'status'=> true];
        }
        Cache::forget(TermService::ALL_VALID_TERM_SERVICE);
        return $this->form()->update($id);
    }
}
