<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    const ORDER_STATUS_UNCONFIRM = 1;
    const ORDER_STATUS_CONFIRM = 2;
    const ORDER_STATUS_SHIPPING = 3;
    const ORDER_STATUS_CHANGE_SHIPPING = 4;
    const ORDER_STATUS_RECEIVE = 5;
    const ORDER_STATUS_REFUSE = 6;
    const ORDER_STATUS_CANCEL = 7;
    const ORDER_STATUS_REFUND = 8;

    const ORDER_STATUS_MAP = [
        self::ORDER_STATUS_UNCONFIRM => '未确认',
        self::ORDER_STATUS_CONFIRM => '已确认未发货',
        self::ORDER_STATUS_SHIPPING => '大陆发货',
        self::ORDER_STATUS_CHANGE_SHIPPING => '转寄发货',
        self::ORDER_STATUS_RECEIVE => '已签收',
        self::ORDER_STATUS_REFUSE => '拒签',
        self::ORDER_STATUS_CANCEL => '取消订单',
        self::ORDER_STATUS_REFUND => '已退件',

    ];

    const ORDER_TYPE_CUSTOMER = 1;
    const ORDER_TYPE_SELF = 2;

    const ORDER_TYPE_MAP = [
        self::ORDER_TYPE_CUSTOMER => '用户下单',
        self::ORDER_TYPE_SELF => '自主录单',
    ];

    const ORDER_COUNTRIES = [
        'sa' => 'Saudi Arabia',
        'ae' => 'The United Arab Emirates',
        'qa' => 'Qatar',
        'kw' => 'Kuwait',
        'bh' => 'Bahrain',
        'om' => 'Oman'
    ];

    const ORDER_EXPORT_HEADER = [
        'order_no' => '订单号',
        'order_cost' => '底价',
        'tracking_number' => '运单号',
        'shipping_cost' => '物流费用',
        'cod_cost' => 'COD费用',
    ];


    public function products(){
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

    protected function addOrder($data){

        DB::transaction(function () use (&$order, $data) {
            $order = new Order();
            $order->order_no = uniqid('on');

            $order->total = $data['total'];
            $order->currency_code = $data['currency_code'];
            $order->customer_name = $data['customer_name'];
            $order->customer_phone = $data['customer_phone'];
            $order->customer_what_apps = $data['whats_app']??'';
            $order->customer_note = $data['address_note']??'';

            if($order_type = array_get($data, 'order_type')){
                $order->order_type = $order_type;
            }

            $order->order_source = '';

            $order->ip = $data['ip'];
            $order->ip_iso_code = $data['ip_iso_code'];

            $order->country = Order::ORDER_COUNTRIES[$data['country_code']];
            $order->address = $data['address'];
            $order->state = array_get($data, 'state', '');
            $order->district = array_get($data, 'district', '');
            $order->city = array_get($data, 'city', '');

            $order->save();
            $order->order_no = $order->genOrderNo();
            $order->save();

            foreach (array_get($data, 'products', []) as $product){
                OrderProduct::addOrderProduct($order, $product);
            }
        });

        return $order;
    }

    public function genOrderNo(){
        $unique = 1;
        $chr = 'NO';
        do {
            $chr .= chr($unique % 26 + 65);
            $unique = floor($unique / 26);
        } while ($unique > 0);
        $order_no = $chr  . str_pad($this->id, 8, 0, STR_PAD_LEFT);
        return strtoupper($order_no);
    }

}
