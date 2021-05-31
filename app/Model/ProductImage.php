<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{

    public $timestamps = false;

    protected $fillable = ['id', 'product_id', 'path'];
}
