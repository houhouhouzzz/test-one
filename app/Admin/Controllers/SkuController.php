<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Model\Sku;
use Illuminate\Http\Request;

class SkuController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Sku';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Sku());

        $grid->column('id', __('Id'));
        $grid->column('product_id', __('Product id'));
        $grid->column('sku', __('Sku'));
        $grid->column('image', __('Image'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Sku::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_id', __('Product id'));
        $show->field('sku', __('Sku'));
        $show->field('image', __('Image'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Sku());

        $form->number('product_id', __('Product id'));
        $form->text('sku', __('Sku'));
        $form->image('image', __('Image'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }

    public function sku(Request $request)
    {
        $q = $request->get('q');

        return Sku::where('sku', 'like', "%$q%")->paginate(null, ['id', 'sku as text']);
    }
}
