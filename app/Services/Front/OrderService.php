<?php

namespace App\Services\Front;

use App\Extensions\Util;
use App\Model\Order;
use App\Model\Sku;

class OrderService
{
    public function addOrder($params)
    {
        $this->preview($params);

        return Order::addOrder($params);
    }

    public function preview(&$params){
        $params['ip'] = Util::getIp();
        $params['ip_iso_code'] = Util::getInfoByIp($params['ip'], 'iso_code');
        $sku = Sku::find(array_get($params, 'sku_id', 0));
        $current_price = array_get($params, 'country_code', 'sa');
        $params['total'] = array_get($params, 'quantity', 1) * $sku->product->{$current_price . '_price'};
        $params['customer_phone'] = array_get($params, 'pre_phone', '') . ' ' .
            array_get($params, 'customer_phone', '');
        $params['products'][] = [
            'product_id' => $sku->product_id,
            'product_name' => $sku->product->title,
            'sku_id' => array_get($params, 'sku_id', 0),
            'quantity' => array_get($params, 'quantity', 1),
            'price' => $sku->product->{$current_price . '_price'}
        ];
        $params['weight_total'] = array_get($params, 'quantity', 1) * $sku->product->weight;
    }
}