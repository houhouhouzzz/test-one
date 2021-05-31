<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\SkuReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index($country=''){
        $query = SkuReturn::with(['sku.product']);
        if($country){
            $query->where('country_code', $country);
        }
        $sku_returns = $query ->groupBy('sku_id')->orderBy('created_at', 'desc')->limit(48)->get();
        return view('front.return.index',
            compact('sku_returns'));

    }
}
