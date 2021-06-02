<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TermService extends Model
{
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;

    const ALL_VALID_TERM_SERVICE = 'ALL_VALID_TERM_SERVICE';

    const STATUS_MAP = [
        self::STATUS_OPEN => '开启',
        self::STATUS_CLOSE => '关闭',
    ];

    CONST SORT_MAP = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
    ];

    protected function getCacheValidAll(){
        return Cache::remember(self::ALL_VALID_TERM_SERVICE, 3600, function (){
            return self::whereStatus(self::STATUS_OPEN)->orderBy('sort')->select(['name'])->get()->toArray();
        });
    }

}
