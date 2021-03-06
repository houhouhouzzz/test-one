<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\Order\BatchStatus;
use App\Admin\Actions\Post\Order\CreateOrder;
use App\Admin\Actions\Post\Order\ExportOrderDataTemplate;
use App\Admin\Actions\Post\Order\ImportOrderData;
use App\Admin\Actions\Post\Order\ViewDetail;
use App\Admin\Actions\Post\Order\Edit;
use App\Admin\Tools\OnceDefaultShipping;
use App\Exporter\OrderExporter;
use App\Exports\OnceDefaultShippingExport;
use App\Extensions\Util;
use App\Model\Order;
use App\Model\OrderProduct;
use App\Model\ShippingMethod;
use App\Model\Sku;
use App\Model\SkuInventory;
use App\Model\Warehouse;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

//        $grid->fixColumns(5, -2);

        $grid->expandFilter();
        $grid->disableCreateButton();
        $grid->model()->orderBy('created_at','desc');

        $grid->exporter(new OrderExporter());


        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
            $batch->add(new BatchStatus());
        });


        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ExportOrderDataTemplate());
            $tools->append(new ImportOrderData());
            $tools->append(new CreateOrder());
            $tools->batch(function ($batch) {
                $batch->add('一键发货(合联)', new OnceDefaultShipping());
            });
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            $actions->disableEdit();
            $actions->add(new ViewDetail);
            $actions->add(new Edit);
        });

        $grid->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                $filter->like('customer_phone', '电话');
                $filter->where(function ($query) {
                    $sku_ids = Sku::whereIn('sku', explode(',', $this->input))->pluck('id');
                    $query->whereHas('products',function($query)use($sku_ids){
                        $query->whereIn('sku_id', $sku_ids);
                    });
                }, 'Sku');
                $filter->equal('order_type', '订单类型')->select(Order::ORDER_TYPE_MAP);
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('order_no', '订单号');
                $filter->like('customer_what_apps', 'whatsapp');
                $filter->between('created_at', '下单时间')->datetime();
                $filter->equal('order_status', '订单状态')->select(Order::ORDER_STATUS_MAP);
            });
        });

        $grid->column('id', __('Id'));
        $grid->column('order_type', '订单类型')->display(function ($order_type){
            return Order::ORDER_TYPE_MAP[$order_type]??'';
        });
        $grid->column('order_no', __('订单号'))->expand(function ($model) {
            $products = $model->load(['products.sku'])->products()->get()->map(function ($product) {
                return [$product->sku->sku, $product->quantity, OrderProduct::TYPE_MAP[$product->type]??'常规商品'];
            });
            return new Table(['Sku', '数量', '商品类型'], $products->toArray());
        });
        $grid->column('products', '商品名称')->display(function ($products) {
            $product = current($products);
            return "<span style='color:blue'> <a href='".Util::to('list/product/' . $product['product_id'])."' target='_blank'> ".
                str_limit($product['product_name'])
                ."</a></span>";
        });
        $grid->column('customer_name', __('用户名称'));
        $grid->column('customer_phone', __('用户电话'))->display(function($customer_phone){
            return $customer_phone . '<br><a class="btn btn-info btn-sm" target="_blank" href="https://web.whatsapp.com/send?phone='.$customer_phone.'">调用whatsApp</a>';
        });
        $grid->column('customer_what_apps', __('whatsapp'))->editable();

        $grid->column('address', '用户地址')->display(function ($address) {
            return "<textarea id='' cols='30' rows='6' disabled>
country : $this->country
state : $this->state
district : $this->district
city : $this->city
address : $address
</textarea>";
        });
        $grid->column('customer_note', __('用户留言'))->editable();
        $grid->column('sku', '购买sku')->display(function (){
            $display = [];
            $order_products = $this->products->toArray();
            $order_product_sku_ids = array_column($order_products, 'sku_id');
            $sku_maps = array_column(Sku::whereIn('id', $order_product_sku_ids)->get(['id', 'sku'])->toArray(), 'sku', 'id');
            foreach ($order_products as $order_product){
                if(!empty($sku_maps[$order_product['sku_id']])){
                    $display[] = $sku_maps[$order_product['sku_id']] . (($order_product['type']==OrderProduct::TYPE_GIFT)?'(赠)':'');
                }
            }
            return join('<br>', $display);
        });

        $grid->column('sku_inventory' , '库存情况')->display(function(){
            $sku_infos = $this->products->toArray();
            foreach ($sku_infos as $sku_info){
                $sku_id = array_get($sku_info, 'sku_id');
                $sku = data_get($sku_info, 'sku.sku', '');
                $inventories = SkuInventory::with('warehouse')->where('sku_id', $sku_id)->get();
                $tmp = [];
                foreach ($inventories as $inventory){
                    $tmp[] = $inventory->warehouse->name . ':' . $inventory->inventory;
                }
                $display[] = $sku . join(',', $tmp);
            }
            return sprintf('<div style="width:150px"><a target="_blank" href="%s">%s</a></div>', Util::to('admin/purchases/create'), join('<br>', $display));
        });

        $grid->column('total', __('购买总价'))->display(function($total){
            return Util::currencyFormat($total, $this->currency_code)['price'];
        });

        $grid->column('created_at', __('下单时间'));
        $grid->column('order_status', __('订单状态'))
            ->editable('select', Order::ORDER_STATUS_MAP);
//        $grid->column('order_source', __('订单来源'));
        $grid->column('note', __('订单备注'))->editable();

        $grid->column('shipping_method_id', __('物流公司'))
            ->editable('select', ShippingMethod::SHIPPING_METHOD_MAP);
        $grid->column('tracking_number', __('物流运单号'))->editable();
        $grid->column('shipping_status', __('物流状态'))
            ->editable('select', ShippingMethod::SHIPPING_STATUS);
        $grid->column('shipping_at', __('发货时间'));
        $grid->column('ip', __('下单ip'))->display(function($ip){
            return $ip . '<br>' . $this->ip_iso_code;
        });

        $grid->header(function ($query) {
            $count = $query->count();
            return '订单总条数: ' . $count . '条';
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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_no', __('Order no'));
        $show->field('total', __('Total'));
        $show->field('order_status', __('Order status'));
        $show->field('order_source', __('Order source'));
        $show->field('currency_code', __('Currency code'));
        $show->field('customer_name', __('Customer name'));
        $show->field('customer_what_apps', __('Customer what apps'));
        $show->field('customer_note', __('Customer note'));
        $show->field('shipping_method_id', __('Shipping method id'));
        $show->field('shipping_status', __('Shipping status'));
        $show->field('tracking_number', __('Tracking number'));
        $show->field('ip', __('Ip'));
        $show->field('lat_lon', __('Lat lon'));
        $show->field('country', __('Country'));
        $show->field('state', __('State'));
        $show->field('district', __('District'));
        $show->field('city', __('City'));
        $show->field('address', __('Address'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $this->script = <<<EOT

EOT;


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

//        $form->text('order_no', __('订单id'));
        $form->decimal('total', __('总金额'));
        $form->select('order_status', __('订单状态'))->options(Order::ORDER_STATUS_MAP);
        $form->text('order_source', __('订单来源'));
        $form->text('currency_code', __('币种'));
        $form->text('customer_name', __('客户名称'));
        $form->text('customer_what_apps', __('what apps'));
//        $form->switch('shipping_status', __('Shipping status'));
//        $form->text('tracking_number', __('Tracking number'));
        $form->ip('ip', __('Ip'));
        $form->text('lat_lon', __('Lat lon'));
        $form->text('country', __('Country'));
        $form->text('state', __('State'));
        $form->text('district', __('District'));
        $form->text('city', __('City'));
        $form->text('address', __('Address'));
        Admin::script(
            <<<JS
(function () {
    $('input[name="lat_lon"]').on('input', (e)=>{
        console.log($(e.target), this);
        // let data = this.value;
        // let arr = data.split(',');
        // if(arr.length < 2){
        //     return;
        // }
        /**
        * 此处处理逻辑
*/
         // let url = sprintf( 'https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=%s&longitude=%s&localityLanguage=zh', arr[0], arr[1]);
        //axios.get(url)->
        // console.log(this.value);
        $('input[name="country"]').value = 'UNITED STATE';
        $('input[name="state"]').value = 'NEW YORK';
        $('input[name="city"]').value = 'xxx';
        $('input[name="district"]').value = 'xxx';
    })
})();
JS
        );

        return $form;
    }

    public function update($id)
    {
        $post = request()->all();
        if (!empty($post['value']) && !empty($post['name'])) {
            $order = Order::findOrFail($id);
            if($post['name'] == 'order_status'
                && ! in_array( $order->status, [Order::ORDER_STATUS_SHIPPING, Order::ORDER_STATUS_CHANGE_SHIPPING])
                && in_array($post['value'], [Order::ORDER_STATUS_SHIPPING, Order::ORDER_STATUS_CHANGE_SHIPPING])
                && empty($order->shipping_at)
            ){
                $order->shipping_at = Carbon::now();
                $warehouse_id = Warehouse::DEFAULT_WAREHOUSE_ID;
                if($post['value'] == Order::ORDER_STATUS_SHIPPING){
                    $order->shipping_status = ShippingMethod::DEFAULT_STATUS;
                }
                if($post['value'] == Order::ORDER_STATUS_CHANGE_SHIPPING){
                    $order->shipping_status = ShippingMethod::EXCHANGE_STATUS;
                    $warehouse_id = Warehouse::DEFAULT_EXCHANGE_ID;
                }
                foreach ($order->products as $product){
                    SkuInventory::incrementInventory($product->sku_id, - $product->quantity, $warehouse_id);
                }
            }
            $order->{$post['name']} = $post['value'];
            $order->save();
            return ['display' => [], 'message' => "更新成功 !", 'status' => true, 'refresh'=>true];
        }
    }

    public function OnceDefaultShipping(Request $request){
        foreach (Order::where('order_status', '<=', Order::ORDER_STATUS_SHIPPING)->find($request->get('ids')) as $model) {
            DB::transaction(function () use($model) {
                if($model->order_status < Order::ORDER_STATUS_SHIPPING){
                    foreach ($model->products as $product){
                        SkuInventory::incrementInventory($product->sku_id, - $product['quantity']);
                    }
                }
                $model->order_status = Order::ORDER_STATUS_SHIPPING;
                $model->shipping_method_id = ShippingMethod::SHIPPING_HELIAN;
                $model->shipping_status = ShippingMethod::DEFAULT_STATUS;
                $model->save();
            });
        }
    }

    public function OnceDefaultShippingExport(Request $request){
        return Excel::download(new OnceDefaultShippingExport($request->get('ids')), '一键合联.xlsx');
    }
}
