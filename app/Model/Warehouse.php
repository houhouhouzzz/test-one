<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Warehouse extends Model
{
    const DEFAULT_WAREHOUSE_ID = 1;
    const DEFAULT_EXCHANGE_ID = 2;

    const ALL_WAREHOUSE = 'ALL_WAREHOUSE';

    protected function getCacheValidAll(){
        return Cache::remember(self::ALL_WAREHOUSE, 3600, function (){
            return self::select(['id','name'])->get()->toArray();
        });
    }

}
