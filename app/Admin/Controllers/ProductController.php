<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\Product\JumpCategoryUrl;
use App\Admin\Actions\Post\Product\JumpProductUrl;
use App\Admin\Actions\Post\Product\ProductEdit;
use App\Admin\Actions\Post\Replicate;
use App\Extensions\Util;
use App\Model\Category;
use App\Model\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->expandFilter();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableExport();

        $grid->actions(function($tool){
            $tool->disableDelete();
            $tool->disableView();
            $tool->disableEdit();
            $tool->add(new ProductEdit);
            $tool->add(new Replicate);
            $tool->add(new JumpCategoryUrl);
            $tool->add(new JumpProductUrl);
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append('<a href="/admin/products/create" target="_blank" class="btn btn-sm btn-success" title="新增">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
    </a>');
        });

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(1/3, function ($filter) {
                $filter->equal('category_id', '分类')->select('api/category');
            });

            $filter->column(1/3, function ($filter) {
                $filter->like('title', '标题');
            });

            $filter->column(1/3, function ($filter) {
                $filter->like('skus.sku', 'Sku');
            });

            // 在这里添加字段过滤器

//            $filter->equal('status')->select(WhatApp::$status_maps);

        });
        $grid->setActionClass(Grid\Displayers\DropdownActions::class);
        $grid->model()->orderBy('created_at','desc');
        $grid->column('id', __('产品编号'))->sortable();
        $grid->column('pictures', '图片')->display(function($pictures){
            if(!empty($pictures)){
                return Util::to($pictures[0]);
            }
            return '';
        })->image();
        $grid->column('product_no', __('货号'))->sortable();
        $grid->column('title', __('产品名称'));

        $grid->column('category_id', __('admin.category_id'))
            ->editable('select', Category::formatKeyValue('id', 'name'));


        $grid->column('in_list', __('推荐列表'))
            ->editable('select', Product::in_list_maps());

        $grid->column('supplier.link', '采购链接')->display(function ($link){
            return sprintf('<a target="_blank" href="%s">%s</a>', $link, $link);
        });


        $grid->column('created_at', __('上架时间'));

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
        $show = new Show(Product::findOrFail($id));

//        $show->field('id', __('Id'));
//        $show->field('title', __('title'));
//        $show->field('category_id', __('category id'));
//        $show->field('is_ele', __('Is ele'));
//        $show->field('description', __('Description'));
//        $show->field('sa_price', __('Sa price'));
//        $show->field('ae_price', __('Ae price'));
//        $show->field('qa_price', __('Qa price'));
//        $show->field('kw_price', __('Kw price'));
//        $show->field('bh_price', __('Bh price'));
//        $show->field('cost', __('Cost'));
//        $show->field('weight', __('Weight'));
//        $show->field('created_at', __('Created at'));
//        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());
        $form->tools(function (Form\Tools $tools) {
            $tools->append('<a class="btn btn-sm btn-info" target="_blank" href="/admin/categories/create" >创建分类</a>');
            $tools->append('<a class="btn btn-sm btn-danger" target="_blank" href="https://www.hsbianma.com" >海关编码查询</a>');
        });
        $form->hidden('product_no');

//        $form->row(function ($row) use($form) {
//
//            $row->width(12)->text('title', __('标题'))->rules('required', [
//                'required' => '标题必填',
//            ]);
//
//            $row->width(7)->select('category_id', '分类')
//                ->options(category::formatKeyValue('id', 'name', false))
//                ->rules('required|min:0', [
//                    'required' => '分类必选',
//                    'min'   => '该分类不符合规范',
//                ]);
//
//            $row->width(6)->radio('is_ele', '是否带电')->options(Product::ele_maps())->default(Product::NOT_ELE);
//
//            $row->width(6)->text('ocean_number', '海关编码');
//
//            $row->width(12)->text('description', __('产品英文描述'));
//            $row->width(3)->decimal('sa_price', __('产品价格 SA'))->placeholder('请输入 SA 价格');
//            $row->width(3)->decimal('ae_price', __('产品价格 AE'))->placeholder('请输入 AE 价格');
//            $row->width(3)->decimal('qa_price', __('产品价格 QA'))->placeholder('请输入 QA 价格');
//            $row->width(3)->decimal('kw_price', __('产品价格 KW'))->placeholder('请输入 KW 价格');
//            $row->width(3)->decimal('bh_price', __('产品价格 BH'))->placeholder('请输入 BH 价格');
//            $row->width(12)->decimal('cost', __('进货底价'))->placeholder('请输入 进货底价')->rules('required|min:1', [
//                'required' => '进货底价必填',
//                'min'   => '进货底价最小为1',
//            ]);
//            $row->width(4)->decimal('weight', __('重量(单位kg)'))->rules('required', [
//                'required' => '重量必填',
//            ]);
//            $row->width(4)->text('supplier.link', __('供应商链接'));
//            $row->width(4)->text('supplier.note', __('采购备注'));
//            $row->width(12)->multipleImage('pictures')->removable();
//
//            $row->multipleSelect('options', '属性')->options([
//                1 => '中国',
//                2 => '外国',
//            ])->when([1, 2], function ()use($form) {
//
//                $form->row(function($row2){
//                    $row2->text('name', '姓名1');
//                });
////                $form->text('idcard', '身份证');
//            })->when('has', 2, function ()use($row) {
//
//                $row->text('name', '姓名2');
////                $form->text('passport', '护照');
//
//            });
//
//            $row->width(12)->hasMany('skus', 'eaon', function (Form\NestedForm $form) use($row) {
//                $row->width(4)->text('id')->setWidth(4,2);
//                $row->width(4)->image('product_id')->setWidth(4,2);
//                $row->width(4)->text('sku');
//            });
//
//
//        }, $form);


        $form->text('title', __('标题'))->rules('required', [
            'required' => '标题必填',
        ]);

        $form->select('category_id', '分类')
            ->options(Category::formatKeyValue('id', 'name', false))
            ->rules('required|min:0', [
                'required' => '分类必选',
                'min'   => '该分类不符合规范',
            ]);

        $form->radio('is_ele', '是否带电')->options(Product::ele_maps())->default(Product::NOT_ELE);

        $form->text('ocean_number', '海关编码');

        $form->text('description', __('产品英文描述'));
//        $form->decimal('sa_price', __('产品价格 SA'))->placeholder('请输入 SA 价格');
//        $form->decimal('ae_price', __('产品价格 AE'))->placeholder('请输入 AE 价格');
//        $form->decimal('qa_price', __('产品价格 QA'))->placeholder('请输入 QA 价格');
//        $form->decimal('kw_price', __('产品价格 KW'))->placeholder('请输入 KW 价格');
//        $form->decimal('bh_price', __('产品价格 BH'))->placeholder('请输入 BH 价格');
        $form->decimal('cost', __('进货底价'))->placeholder('请输入 进货底价')->rules('required|min:1', [
            'required' => '进货底价必填',
            'min'   => '进货底价最小为1',
        ]);
        $form->decimal('weight', __('重量(单位kg)'))->rules('required', [
            'required' => '重量必填',
        ]);
        $form->text('supplier.link', __('供应商链接'));
        $form->text('supplier.note', __('采购备注'));
        $form->multipleImage('pictures')->removable();

        $form->multipleSelect('options', '属性')->options([
            1 => '中国',
            2 => '外国',
        ])->when([1], function ()use($form) {

            $form->text('title', '姓名1');
////                $form->text('idcard', '身份证');
        })->when('has', 2, function ()use($form) {
//
            $form->text('title', '姓名2');
////                $form->text('passport', '护照');
//
        });

//        $form->hasMany('skus', 'eaon', function (Form\NestedForm $form) use($form) {
//            $form->text('id')->setWidth(4,2);
//            $form->image('product_id')->setWidth(4,2);
//            $form->text('sku');
//        });




//        Admin::script("$(document.body).append(`<script src='/admin/js/multiOptions.js'>`);");

//        $form->saving(function (Form $form) {
//            if(!$form->product_no){
//                $form->product_no = uniqid('pr');
//            }
//
//        });
        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();

            // 去掉`查看`checkbox
            $footer->disableViewCheck();

        });

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        return $form;
    }


    public function create(Content $content)
    {
        return $content
            ->header('商品')
            ->description('创建')
            ->body(view('product.edit', ['name' => '初始化数据', 'data' => []])->render());
    }

    public function store(){
        request()->validate(
            [
                'product_no' => 'required|string',
                'title' => 'required|string',
                'category_id' => 'required|int',
                'cost' => 'required|numeric',
                'weight' => 'required|numeric',
                'sa_price' => 'required|numeric',
                'ae_price' => 'required|numeric',
                'qa_price' => 'required|numeric',
                'kw_price' => 'required|numeric',
                'bh_price' => 'required|numeric',
                'om_price' => 'required|numeric',
//                'options' => 'required|array|min:1'
            ],[
                'product_no.required' => '货号必填',
                'product_no.string' => '标题必须为字符串',
                'title.required' => '标题必填',
                'title.string' => '标题必须为字符串',
                'category_id.required' => '分类必选',
                'category_id.int' => '该分类不符合规范',
                'cost.required' => '进货底价必填',
                'cost.numeric' => '进货底价必须是数字',
                'weight.required' => '重量必填',
                'weight.numeric' => '重量必须是数字',
                'sa_price.required' => 'sa 价格必填',
                'ae_price.required' => 'ae 价格必填',
                'qa_price.required' => 'qa 价格必填',
                'kw_price.required' => 'kw 价格必填',
                'bh_price.required' => 'bh 价格必填',
                'om_price.required' => 'om 价格必填',
                'options.required' => '属性选择必须选',
//                'options.min' => '属性选择最少选择一个',
            ]
        );

//        try{
            $post = request()->all();
            $product_id = Product::modify(new Product(),$post);
//        }catch (\Exception $e){
//            return response()->json(['message' => $e->getMessage()], 406);
//        }

        return response()->json(Product::find($product_id), 200);
    }

    public function update($id){
        $post = request()->all();
        if(isset($post['value']) && !empty($post['name'])){
            $product = Product::findOrFail($id);
            $product->{$post['name']} = $post['value'];
            $product->save();
            return ['display'=> [], 'message'=> "更新成功 !", 'status'=> true];
        }
        request()->validate(
            [
                'product_no' => 'required|string',
                'title' => 'required|string',
                'category_id' => 'required|int',
                'cost' => 'required|numeric',
                'weight' => 'required|numeric',
                'sa_price' => 'required|numeric',
                'ae_price' => 'required|numeric',
                'qa_price' => 'required|numeric',
                'kw_price' => 'required|numeric',
                'bh_price' => 'required|numeric',
                'om_price' => 'required|numeric',
//                'options' => 'required|array|min:1'
            ],[
                'product_no.required' => '货号必填',
                'product_no.string' => '标题必须为字符串',
                'title.required' => '标题必填',
                'title.string' => '标题必须为字符串',
                'category_id.required' => '分类必选',
                'category_id.int' => '该分类不符合规范',
                'cost.required' => '进货底价必填',
                'cost.numeric' => '进货底价必须是数字',
                'weight.required' => '重量必填',
                'weight.numeric' => '重量必须是数字',
                'sa_price.required' => 'sa 价格必填',
                'ae_price.required' => 'ae 价格必填',
                'qa_price.required' => 'qa 价格必填',
                'kw_price.required' => 'kw 价格必填',
                'bh_price.required' => 'bh 价格必填',
                'om_price.required' => 'om 价格必填',
                'options.required' => '属性选择必须选',
//                'options.min' => '属性选择最少选择一个',
            ]
        );

        $product = Product::findOrFail($id);
        try{
            $post = request()->all();
            $product_id = Product::modify($product,$post);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 406);
        }

        return response()->json(Product::find($product_id), 200);
    }


    public function edit($id, Content $content)
    {
        $product = Product::with(['options', 'skus', 'skus.options.option', 'supplier'])
            ->findOrFail($id);
        return $content
            ->header('商品')
            ->description('编辑')
            ->body(view('product.edit', ['name' => '初始化数据', 'product' => $product])->render());
    }

    public function product(Request $request){
        $q = $request->get('q');
        return Product::where('product_no', 'like', "%$q%")->paginate(null, ['id', 'product_no as text']);
    }

}
