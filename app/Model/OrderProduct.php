<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = [
        'product_id' ,
        'product_name' ,
        'sku_id' ,
        'quantity' ,
        'type' ,
        'price'
    ];

    public $timestamps = false;

    CONST TYPE_NORMAL = 'normal';
    CONST TYPE_GIFT = 'gift';

    const TYPE_MAP = [
        self::TYPE_NORMAL => '常规商品',
        self::TYPE_GIFT => '赠品',
    ];

    protected function addOrderProduct(Order $order, $data)
    {
        $order_product = new OrderProduct();
        $order_product->order_id = $order->id;
        $order_product->product_id = $data['product_id'];
        $order_product->product_name = $data['product_name'];
        $order_product->sku_id = $data['sku_id'];
        $order_product->quantity = $data['quantity'];
        $order_product->price = $data['price'];
        $order_product->type = array_get($data, 'type', OrderProduct::TYPE_NORMAL);
        $order_product->save();
    }

    public function sku(){
        return $this->belongsTo(Sku::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
