<?php

namespace App\Http\Controllers\Front;

use App\Extensions\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\OrderCreateRequest;
use App\Model\Order;
use App\Model\Product;
use App\Model\WhatApp;
use App\Services\Front\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(OrderCreateRequest $request){

        $params = $request->all();
        $params = array_only($params,[
            'sku_id', 'sku', 'quantity', 'currency_code', 'customer_name', 'customer_phone',
            'address_note', 'whats_app', 'country_code', 'address', 'pre_phone', 'state'
        ]);
        return app(OrderService::class)->addOrder($params);
    }
}
