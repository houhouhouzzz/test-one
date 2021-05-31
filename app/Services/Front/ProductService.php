<?php

namespace App\Services\Front;

use App\Model\Product;

class ProductService extends BaseService
{
    public function filter($params){
        $query = Product::query();
        $query->where('pictures', '!=', '[]');
        if(array_get($params, 'category_id', 0)){
            $query->where('category_id', $params['category_id']);
        }
        if(array_get($params, 'in_list', '*') !== '*'){
            $query->where('in_list', $params['in_list']);
        }
        return $query;
    }
}