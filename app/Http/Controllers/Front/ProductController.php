<?php

namespace App\Http\Controllers\Front;

use App\Extensions\Util;
use App\Http\Controllers\Controller;
use App\Model\Gift;
use App\Model\Product;
use App\Model\TermService;

class ProductController extends Controller
{
    public function index($id, $country=''){
        $product = Product::find($id);
        if(!$product){
            return view('front.product.index',
                compact('product'));
        }
        if(!$country || empty(Product::PRICE_COLUMNS[$country])){
            $country = Util::getCountry();
        }
        $product->load(['skus.options']);
        $price_info = Product::PRICE_COLUMNS[$country];
        $product->price = $product->{$price_info['price_column']};
        $pictures = $product->pictures??[];
        $product->pictures = $pictures;
        $term_services = TermService::getCacheValidAll();
        $gift = Gift::where('main_product_id', $product->id)->whereStatus(Gift::STATUS_OPEN)
            ->first();
        $gift_product = $gift?$gift->gift_product:null;
        if($gift_product){
            $gift_product->gift_title = $gift->title;
        }
        return view('front.product.index',
            compact('product', 'price_info', 'country',
                'term_services', 'gift_product'));
    }

    public function product_list($id){
        $product = Product::find($id);
        $product->pictures;
        $products = [];
        foreach (Product::PRICE_COLUMNS as $key => $value){
            if($product->{$value['price_column']}){
                $product->country = $key;
                $products[] = $product->toArray();
            }
        }
        return view('front.product.list',
            compact('products'));
    }
}
