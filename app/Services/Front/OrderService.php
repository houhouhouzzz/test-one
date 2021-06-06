<?php

namespace App\Services\Front;

use App\Extensions\Util;
use App\Model\Order;
use App\Model\OrderProduct;
use App\Model\Product;
use App\Model\Sku;

class OrderService
{
    public function addOrder($params)
    {
        $this->valid($params);
        $this->preview($params);

        return Order::addOrder($params);
    }

    public function preview(&$params){
        $params['ip'] = Util::getIp();
        $params['ip_iso_code'] = Util::getInfoByIp($params['ip'], 'iso_code');
        $sku = Sku::find(array_get($params, 'sku_id', 0));
        $current_price = array_get($params, 'country_code', 'sa');
        $params['total'] = array_get($params, 'quantity', 1) * $sku->product->{$current_price . '_price'};
        $params['customer_phone'] = array_get($params, 'pre_phone', '') .
            array_get($params, 'customer_phone', '');
        $params['products'][] = [
            'product_id' => $sku->product_id,
            'product_name' => $sku->product->title,
            'sku_id' => array_get($params, 'sku_id', 0),
            'quantity' => array_get($params, 'quantity', 1),
            'price' => $sku->product->{$current_price . '_price'},
            'type' => OrderProduct::TYPE_NORMAL
        ];
        $params['weight_total'] = array_get($params, 'quantity', 1) * $sku->product->weight;

        if(array_get($params, 'gift_sku_id') && array_get($params, 'gift_sku')){
            $gift_sku = Sku::find(array_get($params, 'gift_sku_id', 0));
            $params['products'][] = [
                'product_id' => $gift_sku->product_id,
                'product_name' => $gift_sku->product->title,
                'sku_id' => array_get($params, 'gift_sku_id', 0),
                'quantity' => array_get($params, 'quantity', 1),
                'price' => 0,
                'type' => OrderProduct::TYPE_GIFT
            ];
            $params['weight_total'] += array_get($params, 'quantity', 1) * $gift_sku->product->weight;
        }
    }

    public function valid(&$params){
        $product = Product::find(array_get($params, 'product_id', 0));
        if(!$product){
            throw new \Exception('THE PRODUCT NOT FOUND');
        }
        $sku_options = array_get($params, 'sku_options', []);
        $sku_value = array_get($params, 'main_option_value', '') . join('', $sku_options);
        $sku = Sku::whereSku( $product->product_no . '-' . $sku_value)->first();
        if(!$sku){
            throw new \Exception('THE PRODUCT NOT FOUND');
        }
        $sku = $sku->load('options')->toArray();
        $system_sku_options = array_get($sku, 'options', []);
        if(count($system_sku_options) != count($sku_options)){
            throw new \Exception('THE PRODUCT NOT FOUND, PLEASE TRY AGAIN LATER');
        }
        foreach ($system_sku_options as $system_sku_option){
            if($system_sku_option['option_value'] != array_get($sku_options, $system_sku_option['option_id'], '')){
                throw new \Exception('THE PRODUCT NOT FOUND, PLEASE TRY AGAIN LATER');
            }
        }
        $params['sku'] = array_get($sku, 'sku', '');
        $params['sku_id'] = array_get($sku, 'id', 0);

        if(array_get($params, 'gift_product_id', 0)){
            $gift_product = Product::find(array_get($params, 'gift_product_id', 0));
            if(!$gift_product){
                throw new \Exception('THE GIFT PRODUCT NOT FOUND');
            }
            $gift_sku_options = array_get($params, 'gift_sku_options', []);
            $gift_sku_value = array_get($params, 'gift_main_option_value', '') . join('', $gift_sku_options);
            $gift_sku = Sku::whereSku( $gift_product->product_no . '-' . $gift_sku_value)->first();
            if(!$gift_sku){
                throw new \Exception('THE GIFT PRODUCT NOT FOUND');
            }
            $gift_sku = $gift_sku->load('options')->toArray();
            $system_gift_sku_options = array_get($gift_sku, 'options', []);
            if(count($system_gift_sku_options) != count($gift_sku_options)){
                throw new \Exception('THE GIFT PRODUCT NOT FOUND, PLEASE TRY AGAIN LATER');
            }
            foreach ($system_gift_sku_options as $system_gift_sku_option){
                if($system_gift_sku_option['option_value'] != array_get($gift_sku_options, $system_gift_sku_option['option_id'], '')){
                    throw new \Exception('THE GIFT PRODUCT NOT FOUND, PLEASE TRY AGAIN LATER');
                }
            }
            $params['gift_sku'] = array_get($gift_sku, 'sku', '');
            $params['gift_sku_id'] = array_get($gift_sku, 'id', 0);
        }
    }
}