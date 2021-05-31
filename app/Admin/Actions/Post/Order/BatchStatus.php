<?php

namespace App\Admin\Actions\Post\Order;

use App\Model\Order;
use App\Model\Purchase;
use App\Model\ShippingMethod;
use App\Model\SkuInventory;
use App\Model\Warehouse;
use Carbon\Carbon;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BatchStatus extends BatchAction
{
    public $name = '批量状态变更';

    public function handle(Collection $collection)
    {
        $order_status = request()->get('order_status');
        foreach ($collection as $model) {
            if($order_status > $model->order_status){
                if(in_array( $order_status, [Order::ORDER_STATUS_SHIPPING, Order::ORDER_STATUS_CHANGE_SHIPPING]) &&
                    ! in_array( $model->order_status, [Order::ORDER_STATUS_SHIPPING, Order::ORDER_STATUS_CHANGE_SHIPPING])){
                    $model->shipping_at = Carbon::now();
                    $warehouse_id = Warehouse::DEFAULT_WAREHOUSE_ID;
                    if($order_status == Order::ORDER_STATUS_SHIPPING){
                        $model->shipping_status = ShippingMethod::DEFAULT_STATUS;
                    }
                    if($order_status == Order::ORDER_STATUS_CHANGE_SHIPPING){
                        $model->shipping_status = ShippingMethod::EXCHANGE_STATUS;
                        $warehouse_id = Warehouse::DEFAULT_EXCHANGE_ID;
                    }
                    foreach ($model->products as $product){
                        SkuInventory::incrementInventory($product->sku_id, - $product->quantity, $warehouse_id);
                    }
                    $model->order_status = $order_status;
                }elseif(!in_array( $order_status, [Order::ORDER_STATUS_SHIPPING, Order::ORDER_STATUS_CHANGE_SHIPPING])){
                    $model->order_status = $order_status;
                }
                $model->save();
            }
        }

        return $this->response()->success('订单状态变更成功')->refresh();
    }

    public function form()
    {
        $this->select('order_status', '订单状态')->rules('required',[
            'required' => '订单状态必选'
        ])->options(Order::ORDER_STATUS_MAP);


    }


}