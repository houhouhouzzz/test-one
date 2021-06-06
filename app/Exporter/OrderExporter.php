<?php


namespace App\Exporter;

use App\Extensions\Util;
use App\Model\Order;
use App\Model\OrderProduct;
use App\Model\ShippingMethod;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = 'orders.xlsx';

    protected $headings = ['订单号', '国家', 'sku', '购买价格', '底价',
        '物流费用', 'COD费用', '订单状态', '物流商', '物流运单号', '发货类型', '下单时间', '发货时间'
    ];

    public function map($order) : array
    {
        $order->load(['products.sku', 'products.product']);
        $display_sku = [];
        $first_product = $order->products->first();
        $order_products = $order->products->toArray();
        foreach ($order_products as $order_product){
            $display_sku[] = data_get($order_product, 'sku.sku', '') . (($order_product['type']==OrderProduct::TYPE_GIFT)?'(赠)':'');
        }
        return [
            $order->order_no,
            $order->country,
            join('/', $display_sku),
            Util::currencyFormat($order->total, $order->currency_code)['price'],
            $order->order_cost != 0?$order->order_cost:$first_product->product->cost * $first_product->quantity ,
            $order->shipping_cost,
            $order->cod_cost,
            Order::ORDER_STATUS_MAP[ $order->order_status]??'',
            ShippingMethod::SHIPPING_METHOD_MAP[$order->shipping_method_id]??'',
            $order->tracking_number,
            ShippingMethod::SHIPPING_STATUS[$order->shipping_status]??'',
            $order->created_at,
            $order->shipping_at
        ];
    }


}