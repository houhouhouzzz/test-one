<?php

namespace App\Admin\Actions\Post\Order;

use App\Model\Order;
use App\Model\OrderProduct;
use App\Model\Product;
use App\Model\Sku;
use Encore\Admin\Actions\RowAction;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Edit extends RowAction
{
    public $name = '编辑信息';

    public function handle(Model $model)
    {
        $post = request()->all();
        DB::transaction(function () use($model, $post) {
            $model->customer_name = array_get($post, 'customer_name', '');
            $model->customer_name = array_get($post, 'customer_phone', '');
            $model->country =  Order::ORDER_COUNTRIES[array_get($post, 'country_code', 'sa')];
            $model->total = array_get($post, 'total', '');
            $model->currency_code = array_get($post, 'currency_code', '');
            $model->state = array_get($post, 'state', '');
            $model->district = array_get($post, 'district', '');
            $model->city = array_get($post, 'city', '');
            $model->address = array_get($post, 'address', '');
            if(array_get($post, 'skus', '')){
                $skus = $this->dealCustomerSku($post['skus'], true);
                $model->products()->delete();
                $new_skus = [];
                foreach ($skus as $sku){
                    $new_skus[] = new OrderProduct([
                        'product_id' => $sku['product_id'],
                        'product_name' => $sku['product_name'],
                        'sku_id' => $sku['sku_id'],
                        'quantity' => $sku['quantity'],
                        'type' => $sku['type'],
                        'price' => 0
                    ]);
                }
                $model->products()->savemany($new_skus);
            }
            $model->save();
        });
        return $this->response()->success('编辑信息成功')->refresh();
    }

    public function form()
    {
        $this->row->load(['products.sku']);
        $this->text('customer_name', '用户名称')->rules('required',['required'=>'用户名称必填'])->value($this->row->customer_name);
        $this->text('customer_phone', '用户电话')->rules('required',['required'=>'用户电话必填'])->value($this->row->customer_phone);
        $this->select('country_code', '国家')->value(array_flip(Order::ORDER_COUNTRIES)[$this->row->country]??'sa')->options(Order::ORDER_COUNTRIES);
        $this->text('total', '总价')->rules('required|numeric',[
            'required'=>'地址必填',
            'numeric'=>'总价必须为数值',
        ])->value($this->row->total);
        $this->select('currency_code', '币种')->value($this->row->currency_code)->options(array_column(Product::PRICE_COLUMNS, 'currency_code', 'currency_code'));
        $this->text('state', 'state')->value($this->row->state);
        $this->text('skus', 'skus')->rules([
            'required',
            function ($attribute, $value, $fail) {
                try {
                    $this->dealCustomerSku($value);
                }catch (\Exception $e){
                    return $fail($e->getMessage());
                }
            }
        ])->value($this->formatSku($this->row));
        $this->text('district', 'district')->value($this->row->district);
        $this->text('city', 'city')->value($this->row->city);
        $this->text('address', '地址')->rules('required',['required'=>'地址必填'])->value($this->row->address);
    }

    public function formatSku($order){
        $data = [];
        foreach ($order->products as $product){
            $row = [
                $product->sku->sku,
                $product->quantity
            ];
            if($product->type == OrderProduct::TYPE_GIFT){
                $row[] = 'gift';
            }
            $data[] = join(',', $row);
        }
        return join(';', $data);
    }

    public function dealCustomerSku($value, $need_product_name=false){
        $sku_arr = explode(';', $value);
        array_walk($sku_arr, function (&$sku) use($need_product_name) {
            $sku = explode(',', trim($sku));
            if (!in_array(count($sku), [2, 3])) {
                throw new \Exception(join(',', $sku) . '该sku数据输入有误');
            }
            if(!($sku_model= Sku::whereSku($sku[0])->select(['id', 'product_id'])->first())){
                throw new \Exception('sku: ' . $sku[0] . ' 该sku不存在');
            }
            if(!is_numeric($sku[1]) || $sku[1] < 1){
                throw new \Exception('sku: ' . $sku[0] . ' 后的数量 '.is_int($sku[1]).' 为数字并且必须大于等于1');
            }
            if(!empty($sku[2]) && $sku[2] != 'gift'){
                throw new \Exception('sku: ' . $sku[0]  . ' 后的第二个参数不为gift');
            }
            if($need_product_name){
                $sku['product_name'] = Product::find($sku_model->product_id)->title;
            }
            $sku['product_id'] = $sku_model->product_id;
            $sku['sku_id'] = $sku_model->id;
            $sku['quantity'] = $sku[1];
            $sku['type'] = empty($sku[2])?OrderProduct::TYPE_NORMAL:OrderProduct::TYPE_GIFT;
        });
        return $sku_arr;
    }

    protected function script()
    {
        return <<<JS
(function () {
   // $('input[name="skus"]').on('blue', (e)=>{
   //     let console.log = $(e.target).val();
       
       // if(lat_lon.indexOf(",") != -1){
       //     var lat_lon_arr = lat_lon.split(',');
       //     if(lat_lon_arr.length != 2) return;
       //     let lat = lat_lon_arr[0];
       //     let lon = lat_lon_arr[1];
       //     if(lat && lon){
       //         let url = 'http://api.positionstack.com/v1/reverse?access_key=af87436760c92cc59047959189d4b05e&limit=1&query='+lat_lon_arr.join(',');
       //         $.get(url, function(data,status){
       //             // console.log(data,status);
       //             if(status=='success' && data){
       //                 console.log(data[0])
       //                 $('#country').val(data.data[0].country);
       //                 $('#state').val(data.data[0].region);
       //                 $('#city').val(data.data[0].locality);
       //                 $('#district').val(data.data[0].county);
       //                 $('#address').val(data.data[0].label);
       //                 console.log(data.data[0].label);
       //             }
       //         });
       //     }
       // }
   // })
})();
JS;
    }

}