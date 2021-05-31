<?php

namespace App\Http\Controllers\Front;

use App\Extensions\Util;
use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Services\Front\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index($id, ProductService $service){
        request()->validate([
            'page' => 'nullable|email',
//            'limit' => 'nullable|email',
        ]);
        $country = Util::getCountry();
        $price_info = Product::PRICE_COLUMNS[Util::getCountry()];
        $products = $service->filter(['category_id', $id, 'in_list'=>Product::IN_LIST])
            ->select(['id', 'product_no', 'title', 'pictures', DB::raw($price_info['price_column'] . ' as price')])
            ->paginate(request()->get('self_limit', 30))->appends(request()->all());
        return view('front.category.index',
            compact('products', 'price_info', 'country'));
    }

}
