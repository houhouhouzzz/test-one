<?php

namespace App\Model;

use App\Extensions\Util;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    CONST STATUS_ONLINE = 1;
    CONST STATUS_OFFLINE = 0;

    const NUMBER_PREFIX = 'SN_';

    public function options(){
        return $this->hasMany(SkuOption::class, 'sku_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function inventory(){
        return $this->hasMany(SkuInventory::class);
    }

    public function getConfirmNumberAttribute(){
        return Order::join('order_products', 'order_products.order_id', '=', 'orders.id')
            ->where('orders.status', Order::ORDER_STATUS_CONFIRM)
            ->where('order_products.sku_id', $this->id)->count();
    }


    public function getUnConfirmNumberAttribute(){
        return Order::join('order_products', 'order_products.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(3))
            ->where('orders.status', Order::ORDER_STATUS_UNCONFIRM)
            ->where('order_products.sku_id', $this->id)->count();
    }

    protected function modify($product_id, $data){
        $old_sku_ids = self::where(compact('product_id'))->whereStatus(self::STATUS_ONLINE)->get()->map->id->toArray();
        $new_sku_ids = [];
        foreach ($data as $datum){
            $sku_id = array_get($datum, 'id', 0);
            $sku = self::find($sku_id);
            if(!$sku){
                $sku = new self();
            }
            if(!$sku->id){
                $sku->sku = $this->genSku();
            }
            $sku->image = array_get($datum, 'image', '');
            $sku->product_id = $product_id;
            $sku->save();
            $sku->sku = $sku->genSku();
            $sku->save();
            $options = array_get($datum, 'options', []);
            SkuOption::modify($sku->id, $options);
            $new_sku_ids[] = $sku->id;
            SkuInventory::createSkuInventory($sku_id);

        }
        if($diff_ids = array_diff($old_sku_ids, $new_sku_ids)){
            Sku::whereIn('id', $diff_ids)->update(['status'=> self::STATUS_OFFLINE]);
        }

    }

    public function genSku(){
        if($this->id){
            return Util::getNumber($this->id, Sku::NUMBER_PREFIX);
        }
        return uniqid();
    }

}
