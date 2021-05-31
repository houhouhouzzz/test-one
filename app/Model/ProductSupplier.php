<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    protected function modify($product_id, $data){
        $supplier = self::where(compact('product_id'))->first();
        if(! $supplier){
            $supplier = new self();
        }
        $supplier->product_id = $product_id;
        foreach ($data as $key => $value){
            $supplier->{$key} = $value;
        }
        $supplier->save();
        return $supplier->id;
    }
}
