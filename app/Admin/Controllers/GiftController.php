<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\Gift\GiftEdit;
use App\Model\Gift;
use App\Model\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GiftController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '赠品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Gift());

        $grid->expandFilter();

        $grid->filter(function ($filter){
            $filter->disableIdFilter();

            $filter->column(1/2, function ($filter) {
                $filter->like('main_product.product_no', '主商品');
                $filter->equal('status', '状态')->select(Gift::$status_maps);
            });

            $filter->column(1/2, function ($filter) {
                $filter->like('gift_product.product_no', '赠品');
            });
        });
        $grid->column('title', '小标题');
        $grid->column('main_product', __('主商品'))->display(function ($value){
            return sprintf('<a target="_blank" href="/list/product/%s">%s</a>', $value['id'], $value['product_no']);
        });
        $grid->column('gift_product.product_no', __('赠品'));
        $grid->column('status', __('状态'))
            ->editable('select', Gift::$status_maps);

        $grid->column('created_at', __('admin.created_at'));
        $grid->column('updated_at', __('admin.updated_at'));

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append('<a href="/admin/gifts/create" target="_blank" class="btn btn-sm btn-success" title="新增">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
    </a>');
        });

        $grid->disableRowSelector();

        $grid->disableColumnSelector();

        $grid->disableCreateButton();

        $grid->disableExport();

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->add(new GiftEdit);
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
        $show = new Show(Gift::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('main_product_id', __('Main product id'));
        $show->field('gift_product_id', __('Gift product id'));
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
        $form = new Form(new Gift());

        $form->disableEditingCheck();
        $form->disableViewCheck();
        $form->disableCreatingCheck();

        $form->select('main_product_id', __('主商品'))->options(function ($id) {
            $product = Product::find($id);
            if ($product) {
                return [$product->id => $product->product_no];
            }
        })->ajax('/admin/api/product');
        $form->select('gift_product_id', __('赠品'))->options(function ($id) {
            $product = Product::find($id);
            if ($product) {
                return [$product->id => $product->product_no];
            }
        })->ajax('/admin/api/product');;
        $form->select('status', __('状态'))->options(Gift::$status_maps);

        return $form;
    }

    public function create(Content $content)
    {
        return $content
            ->header('赠品')
            ->description('创建')
            ->body(view('admin.gift.edit', ['name' => '初始化数据', 'data' => []])->render());
    }

    public function store(){
        request()->validate(
            [
                'title' => 'required|string',
                'main_product_no' => 'required|string',
                'gift_product_no' => 'required|string',
                'status' => 'required|in:0,1',
            ],[
                'title.string' => '小标题必填',
                'title.required' => '小标题必填',
                'main_product_no.string' => '主商品(货号)必填',
                'main_product_no.required' => '主商品(货号)必填',
                'gift_product_no.string' => '赠品(货号)必填',
                'gift_product_no.required' => '赠品(货号)必填',
                'status.required' => '状态必选',
                'status.in' => '状态值有误，请重新选择',
            ]
        );

        try{
            $post = request()->all();
            $gift_id = Gift::modify(new Gift(),$post);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 406);
        }

        return response()->json(Gift::find($gift_id), 200);
    }

    public function update($id){
        $post = request()->all();
        if(isset($post['value']) && !empty($post['name'])){
            $gift = Gift::findOrFail($id);
            $gift->{$post['name']} = $post['value'];
            $gift->save();
            return ['display'=> [], 'message'=> "更新成功 !", 'status'=> true];
        }
        request()->validate(
            [
                'title' => 'required|string',
                'main_product_no' => 'required|string',
                'gift_product_no' => 'required|string',
                'status' => 'required|in:0,1',
            ],[
                'title.string' => '小标题必填',
                'title.required' => '小标题必填',
                'main_product_no.string' => '主商品(货号)必填',
                'main_product_no.required' => '主商品(货号)必填',
                'gift_product_no.string' => '赠品(货号)必填',
                'gift_product_no.required' => '赠品(货号)必填',
                'status.required' => '状态必选',
                'status.in' => '状态值有误，请重新选择',
            ]
        );

        $gift = Gift::findOrFail($id);
        try{
            $post = request()->all();
            $gift_id = Gift::modify($gift,$post);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 406);
        }

        return response()->json(Gift::find($gift_id), 200);
    }


    public function edit($id, Content $content)
    {
        $gift = Gift::with(['main_product', 'gift_product'])
            ->findOrFail($id);
        return $content
            ->header('赠品')
            ->description('编辑')
            ->body(view('admin.gift.edit', ['name' => '初始化数据', 'gift' => $gift])->render());
    }
}
