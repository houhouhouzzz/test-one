<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WhatApp extends Model
{
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;

    const ALL_WHAT_APPS = 'ALL_WHAT_APPS';
    const CURRENT_WHAT_APP_NUM = 'CURRENT_WHAT_APP_NUM';


    public static $status_maps =[
        self::STATUS_OPEN => '开启',
        self::STATUS_CLOSE => '关闭'
    ];

    protected function getCurrentWhatApp(){
        $what_app_valid = WhatApp::getCacheValidAll();
        if(!$what_app_valid){
            return '';
        }
        $num = Cache::get(self::CURRENT_WHAT_APP_NUM);
        if(is_null($num)){
            $num = 0;
            Cache::set(self::CURRENT_WHAT_APP_NUM, $num);
        }
        return data_get($what_app_valid, $num . '.phone', '');
    }

    protected function getCacheValidAll(){
        return Cache::remember(self::ALL_WHAT_APPS, 3600, function (){
            return self::whereStatus(self::STATUS_OPEN)->select(['id','phone'])->get()->toArray();
        });
    }

}
