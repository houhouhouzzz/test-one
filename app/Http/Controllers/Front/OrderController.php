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
            'product_id', 'main_option_value', 'sku_options',
            'gift_product_id', 'gift_main_option_value', 'gift_sku_options',
            'quantity', 'currency_code', 'customer_name', 'customer_phone',
            'address_note', 'whats_app', 'country_code', 'address', 'pre_phone', 'state'
        ]);
        try {
            app(OrderService::class)->addOrder($params);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 406);
        }
        return response()->noContent();
    }
}
