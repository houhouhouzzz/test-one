<?php

namespace App\Admin\Actions\Post\Order;

use App\Extensions\Util;
use App\Model\Order;
use App\Model\Product;
use App\Model\Sku;
use Encore\Admin\Actions\Action;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class CreateOrder extends Action
{
    protected $selector = '.create-order';

    public function handle(Request $request)
    {
        $params = $request->all();
        try {
            $this->preview($params);
            Order::addOrder($params);
        }catch (\Exception $e){
            return $this->response()->error($e->getMessage());
        }

        return $this->response()->success('手动创单成功！')->refresh();
    }

    public function preview(&$params)
    {
        $params['order_type'] = Order::ORDER_TYPE_SELF;
        $params['ip'] = Util::getIp();
        $params['ip_iso_code'] = Util::getInfoByIp($params['ip'], 'iso_code');
        $sku = Sku::where('sku', $params['sku'])->first();
        $params['currency_code'] = Product::PRICE_COLUMNS[$params['country_code']]['currency_code'];
        $params['total'] = array_get($params, 'total', 1);
        $params['customer_phone'] = array_get($params, 'customer_phone', '');
        $params['weight_total'] = $sku->product->weight * array_get($params, 'quantity', 1);
        $params['products'][] = [
            'product_id' => $sku->product_id,
            'product_name' => $sku->product->title,
            'sku_id' => $sku->id,
            'quantity' => array_get($params, 'quantity', 1),
            'price' => round($params['total'] / array_get($params, 'quantity', 1), 2)
        ];
    }

    public function form()
    {
        $this->select('order_type', '订单类型')->attribute('disabled="disabled"')
            ->value(Order::ORDER_TYPE_SELF)
            ->options(Order::ORDER_TYPE_MAP);
        $this->text('customer_name', '用户名称')->rules('required', [
            'required' => '用户名称必填',
        ]);
        $this->text('customer_phone', '用户电话')->rules('required', [
            'required' => '用户电话必填',
        ])->placeholder(join('/',array_column( Product::PRICE_COLUMNS, 'pre_phone')) . '开头');
        $this->text('what_apps', 'whatapps');
        $this->text('lat_lon', '经纬度');
        $this->select('country_code', '国家')->value('sa')->options(Order::ORDER_COUNTRIES);
        $this->text('state', 'state');
        $this->text('city', 'city');
        $this->text('district', 'district');
        $this->text('address', '用户地址')->rules('required', [
            'required' => 'Sku必选',
        ]);;
        $this->text('sku', 'Sku')->rules('required|exists:skus,sku', [
            'required' => 'Sku必选',
            'exists' => '该sku不存在',
        ]);
        $this->text('quantity', '数量')->attribute('type="number"')->rules('required|numeric|max:100|min:1', [
            'required' => '数量必填',
            'max' => '最大数量为100',
            'min' => '最小数量为1',
        ]);
        $this->text('total', '总价')->rules('required|numeric|min:0.01',[
            'required' => '总价必填',
            'numeric' => '总价必须为数字',
            'min' => '总价最小值为0.01',
        ]);
        Admin::script($this->script());
    }

    protected function script()
    {
        return <<<JS
(function () {
    $('input[name="lat_lon"]').on('input', (e)=>{
        let lat_lon = $(e.target).val();
        lat_lon = lat_lon.replace(/，/g,',')
        lat_lon = lat_lon.replace(/\s/g,'')
        if(lat_lon.indexOf(",") != -1){
            var lat_lon_arr = lat_lon.split(',');
            if(lat_lon_arr.length != 2) return;
            let lat = lat_lon_arr[0];
            let lon = lat_lon_arr[1];
            if(lat && lon){
                let url = 'http://api.positionstack.com/v1/reverse?access_key=af87436760c92cc59047959189d4b05e&limit=1&query='+lat_lon_arr.join(',');
                $.get(url, function(data,status){
                    // console.log(data,status);
                    if(status=='success' && data){
                        console.log(data[0])
                        $('#country').val(data.data[0].country);
                        $('#state').val(data.data[0].region);
                        $('#city').val(data.data[0].locality);
                        $('#district').val(data.data[0].county);
                        $('#address').val(data.data[0].label);
                        console.log(data.data[0].label);
                    }
                });
            }
        }
    })
})();
JS;
    }


    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-info create-order">自主录单</a>
HTML;
    }
}