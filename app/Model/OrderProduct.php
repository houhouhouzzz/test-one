<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public $timestamps = false;

    protected function addOrderProduct(Order $order, $data)
    {
        $order_product = new OrderProduct();
        $order_product->order_id = $order->id;
        $order_product->product_id = $data['product_id'];
        $order_product->product_name = $data['product_name'];
        $order_product->sku_id = $data['sku_id'];
        $order_product->quantity = $data['quantity'];
        $order_product->price = $data['price'];
        $order_product->save();
    }

    public function sku(){
        return $this->belongsTo(Sku::class);
    }
}
