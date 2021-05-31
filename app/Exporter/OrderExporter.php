<?php


namespace App\Exporter;

use App\Extensions\Util;
use App\Model\Order;
use App\Model\ShippingMethod;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = 'orders.xlsx';

    protected $headings = ['订单号', '国家', 'sku', '购买价格', '底价',
        '物流费用', 'COD费用', '订单状态', '物流商', '发货类型', '下单时间', '发货时间'
    ];

    public function map($order) : array
    {
        return [
            $order->order_no,
            $order->country,
            $order->products->first()->sku->sku,
            Util::currencyFormat($order->total, $order->currency_code)['price'],
            $order->order_cost,
            $order->shipping_cost,
            $order->cod_cost,
            Order::ORDER_STATUS_MAP[ $order->order_status]??'',
            ShippingMethod::SHIPPING_METHOD_MAP[$order->shipping_method_id]??'',
            ShippingMethod::SHIPPING_STATUS[$order->shipping_status]??'',
            $order->created_at,
            $order->shipping_at
        ];
    }


}