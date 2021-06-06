<?php

namespace App\Exports;

use App\Model\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OnceDefaultShippingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ids ;

    public function headings(): array
    {
        return [
            'Consignee', 'ConsigneeName', 'ConsigneeAddress1', 'ConsigneeAddress2',
            'ConsigneeCity', 'ConsigneePhone', 'ConsigneeTel', 'Origin',
            'Destination', 'TotalWeight', 'noofpieces', 'customnote',
            'ShipperRef', 'GoodsDesc', 'weight', 'PCS',
            'ValueOfShipment', 'Description', 'Retail Code', 'ServiceType',
            'InAmt'
        ];
    }

    public function __construct($ids = [])
    {
        $this->ids = $ids;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with(['products.product', 'products.sku'])->where('order_status', '<=', Order::ORDER_STATUS_SHIPPING)
            ->find(explode(',', $this->ids));
    }

    public function map($order): array
    {
        list($product_desc, $product_weight, $product_quantity, $product_cost, $product_sku, $ocean_code)
            = $this->formatProductInfo($order);

        return [
            $order->customer_name,
            $order->customer_name,
            $order->address,
            '',
            $order->city,
            $order->customer_phone,
            '',
            'CZX',
            strtoupper(array_flip(Order::ORDER_COUNTRIES)[$order->country]??'SA'),
            $order->weight_total,
            '1',
            '',
            $order->order_no,
            join(',', $product_desc),
            join(',', $product_weight),
            join(',', $product_quantity),
            join(',', $product_cost),
            join(',', $product_sku),
            join(',', $ocean_code),
            'NCND',
            $order->total
        ];
    }

    public function formatProductInfo(Order $order){
        $product_desc = $product_weight = $ocean_code = $product_quantity = $product_cost = $product_sku =[];
        $order->products->map(function ($product)use(&$product_desc, &$product_weight, &$ocean_code, &$product_quantity, &$product_cost, &$product_sku){
            $product_desc[] = $product->product->description;
            $product_weight[] = $product->product->weight;
            $ocean_code[] = $product->product->ocean_number;
            $product_quantity[] = $product->quantity;
            $product_cost[] = $product->product->cost / 2 * 6.5;
            $product_sku[] = $product->sku->sku;
        });
        return [$product_desc, $product_weight, $product_quantity, $product_cost, $product_sku, $ocean_code];
    }
}
