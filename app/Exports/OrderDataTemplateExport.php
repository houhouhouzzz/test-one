<?php

namespace App\Exports;

use App\Model\Order;
use Maatwebsite\Excel\Concerns\FromArray;

class OrderDataTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            array_values(Order::ORDER_EXPORT_HEADER)
        ];
    }
}
