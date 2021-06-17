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

    protected function modify(Product $product, $data){
        $old_sku_ids = self::where('product_id', $product->id)->whereStatus(self::STATUS_ONLINE)->get()->map->id->toArray();
        $new_sku_ids = [];
        $data = $this->formatSaveSkuData($data);
        foreach ($data as $datum){
            $main_option = array_get( $datum, 'main_option_value', '');
            $option_values = array_column(array_get($datum, 'options', []), 'option_value');
            $options = is_array($option_values)?join('', $option_values):'';
            $sku_no = Sku::genSku($product, $main_option, $options);
            $sku = self::where('sku', $sku_no)->first();
            if(!$sku){
                $sku = new self();
            }
            $sku->sku = $sku->genSku($product, $main_option, $options);
            $sku->main_option = array_get($datum, 'main_option', 0);
            $sku->main_option_value = array_get($datum, 'main_option_value', '');
            $sku->image = array_get($datum, 'image', '');
            $sku->product_id = $product->id;
            $sku->save();
            $options = array_get($datum, 'options', []);
            SkuOption::modify($sku->id, $options);
            $new_sku_ids[] = $sku->id;
            SkuInventory::createSkuInventory($sku->id);
        }
        if($diff_ids = array_diff($old_sku_ids, $new_sku_ids)){
            Sku::whereIn('id', $diff_ids)->update(['status'=> self::STATUS_OFFLINE]);
        }

    }

    public function formatSaveSkuData($data){
        $return_data = [];
        foreach ($data as $datum){
            $main_option = current($datum['main_option']);
            $datum['main_option'] = $main_option?$main_option['option_id']:0;
            $datum['main_option_value'] =$main_option?$main_option['option_value']:'';
            $options = array_filter( array_get($datum, 'options', []));
            $option_map = array_column($options, 'option_value', 'option_id');
            array_walk($option_map , function (&$option_item){
                $option_item = array_filter(explode(',', $option_item));
            });
            $option_explodes = $this->dikaer($option_map);
            if(is_array($option_explodes) && count($option_map) > 1){
                foreach ($option_explodes as $option_explode){
                    $i = 0;
                    $tmp_options = $options;
                    foreach ($tmp_options as $key => $tmp_option){
                        $tmp_options[$key]['option_value'] = $option_explode[$i];
                        $i++;
                    }
                    $return_datum = $datum;;
                    $return_datum['options'] = $tmp_options;
                    $return_data[] = $return_datum;
                }
            }elseif(is_array($option_explodes) && count($option_map) == 1){
                foreach ($option_explodes as $option_explode){
                    $i = 0;
                    $tmp_options = $options;
                    foreach ($tmp_options as $key => $tmp_option){
                        $tmp_options[$key]['option_value'] = $option_explode;
                        $i++;
                    }
                    $return_datum = $datum;;
                    $return_datum['options'] = $tmp_options;
                    $return_data[] = $return_datum;
                }
            } else{
                $return_data[] = array_merge(
                    $datum,
                    ['options' => []]
                );
            }
        }
        return $return_data;
    }

    public function dikaer($arr)
    {
        $arr1 = array();
        $result = array_shift($arr);
        while ($arr2 = array_shift($arr)) {
            $arr1 = $result;
            $result = array();
            foreach ($arr1 as $v) {
                foreach ($arr2 as $v2) {
                    if (!is_array($v)) $v = array($v);
                    if (!is_array($v2)) $v2 = array($v2);
                    $result[] = array_merge_recursive($v, $v2);
                }
            }
        }
        return $result;
    }

    protected function genSku($product, $main_option, $options){
        return $product->product_no . '-' . $main_option . $options;
    }

}
