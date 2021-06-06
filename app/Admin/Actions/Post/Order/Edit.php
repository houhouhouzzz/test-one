<?php

namespace App\Admin\Actions\Post\Order;

use App\Extensions\Util;
use App\Model\Order;
use App\Model\Product;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Edit extends RowAction
{
        public $name = '编辑信息';

    public function handle(Model $model)
    {
        $post = request()->all();
        $model->customer_name = array_get($post, 'customer_name', '');
        $model->customer_name = array_get($post, 'customer_phone', '');
        $model->country =  Order::ORDER_COUNTRIES[array_get($post, 'country_code', 'sa')];
        $model->total = array_get($post, 'total', '');
        $model->currency_code = array_get($post, 'currency_code', '');
        $model->state = array_get($post, 'state', '');
        $model->district = array_get($post, 'district', '');
        $model->city = array_get($post, 'city', '');
        $model->address = array_get($post, 'address', '');
        $model->save();
        return $this->response()->refresh();
    }

    public function form()
    {
        $this->text('customer_name', '用户名称')->rules('required',['required'=>'用户名称必填'])->value($this->row->customer_name);
        $this->text('customer_phone', '用户电话')->rules('required',['required'=>'用户电话必填'])->value($this->row->customer_phone);
        $this->select('country_code', '国家')->value(array_flip(Order::ORDER_COUNTRIES)[$this->row->country]??'sa')->options(Order::ORDER_COUNTRIES);
        $this->text('total', '总价')->rules('required|numeric',[
            'required'=>'地址必填',
            'numeric'=>'总价必须为数值',
        ])->value($this->row->total);
        $this->select('currency_code', '币种')->value($this->row->currency_code)->options(array_column(Product::PRICE_COLUMNS, 'currency_code', 'currency_code'));
        $this->text('state', 'state')->value($this->row->state);
        $this->text('district', 'district')->value($this->row->district);
        $this->text('city', 'city')->value($this->row->city);
        $this->text('address', '地址')->rules('required',['required'=>'地址必填'])->value($this->row->address);

    }

}