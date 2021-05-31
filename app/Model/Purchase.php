<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    const STATUS_PROCESSING = 1;
    const STATUS_ARRIVE = 6;
    const STATUS_DELETE = 9;

    const STATUS_MAP = [
        self::STATUS_PROCESSING => '采购中',
        self::STATUS_ARRIVE => '已入库',
    ];
}
