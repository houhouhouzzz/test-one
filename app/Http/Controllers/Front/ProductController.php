<?php

namespace App\Http\Controllers\Front;

use App\Extensions\Util;
use App\Http\Controllers\Controller;
use App\Model\Product;

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
        $price_info = Product::PRICE_COLUMNS[$country];
        $product->price = $product->{$price_info['price_column']};
        $pictures = $product->pictures??[];
        $product->top_picture = array_get($pictures, 0, '');
        unset( $pictures['0']);
        $product->pictures = $pictures;

        return view('front.product.index',
            compact('product', 'price_info', 'country'));

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
