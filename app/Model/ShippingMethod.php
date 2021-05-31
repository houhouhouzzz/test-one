<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    // 合联
    const SHIPPING_HELIAN = 1;

    const SHIPPING_METHOD_MAP = [
        self::SHIPPING_HELIAN => '合联',
    ];

    const DEFAULT_STATUS = 1;
    const EXCHANGE_STATUS = 2;

    const SHIPPING_STATUS = [
        self::DEFAULT_STATUS => '大陆发货',
        self::EXCHANGE_STATUS => '转寄'
    ];
}
