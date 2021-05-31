<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Option extends Model
{
    const CACHE_ALL_KEY = 'OPTION_CACHE_ALL';
    //
    public static function getCacheAll(){
        return Cache::remember(self::CACHE_ALL_KEY, 60, function (){
            return self::all()->toArray();
        });
    }

    public static function formatKeyValue($key, $value = null, $cache=true){
        if($cache){
            return array_column( self::getCacheAll(), $value, $key)?:[];
        }
        return array_column( self::all()->toArray(), $value, $key)?:[];
    }
}
