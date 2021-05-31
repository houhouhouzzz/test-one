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
        return [
            'guangzhou nashat',
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
            $order->products->first()->product->title,
            $order->weight_total??$order->products->first()->product->weight*$order->products->first()->quantity,
            $order->products->first()->quantity,
            $order->products->first()->product->cost * $order->products->first()->quantity,
            $order->products->first()->sku->sku,
            $order->products->first()->product->ocean_number,
            'NCND',
            $order->total
        ];
    }
}
