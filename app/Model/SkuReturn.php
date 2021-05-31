<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SkuReturn extends Model
{

    protected $fillable = ['sku_id', 'origin_tracking_number', 'country_code', 'shipping_method_id'];

    const SKU_RETURN_EXPORT_HEADER  = [
        'tracking_number' => '运单号',
    ];

    public function sku(){
        return $this->belongsTo(Sku::class);
    }
}
