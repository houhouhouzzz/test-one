<?php

namespace App\Exports;

use App\Model\Order;
use App\Model\SkuReturn;
use Maatwebsite\Excel\Concerns\FromArray;

class SkuReturnTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            array_values(SkuReturn::SKU_RETURN_EXPORT_HEADER)
        ];
    }
}
