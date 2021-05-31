<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SkuInventory extends Model
{
    protected $fillable = ['sku_id', 'warehouse_id'];

    public $timestamps = false;

    protected function createSkuInventory($sku_id, $warehouse_id=Warehouse::DEFAULT_WAREHOUSE_ID){
        $params = ['sku_id' => $sku_id, 'warehouse_id'=>$warehouse_id];
        $sku_inventory = self::where($params)->first();
        if(!$sku_inventory){
            $sku_inventory = self::create($params);
        }
        return $sku_inventory;
    }

    protected function incrementInventory($sku_id, $increment_inventory, $warehouse_id=Warehouse::DEFAULT_WAREHOUSE_ID){
        $sku_inventory = self::createSkuInventory($sku_id, $warehouse_id);
        $sku_inventory->increment('inventory', $increment_inventory);
    }

    public function sku(){
        return $this->belongsTo(Sku::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }
}
