<?php

namespace App\Model;

use App\Extensions\Util;
use Illuminate\Database\Eloquent\Model;
use DB;
use phpDocumentor\Reflection\Types\Integer;

class Product extends Model
{

    const NOT_ELE = 0;
    const IS_ELE = 1;

    const NUMBER_PREFIX = 'PN_';

    const PRICE_COLUMNS = [
        'sa' => ['price_column' => 'sa_price', 'currency_code'=>'SAR', 'pre_phone' => "+966", 'phone_length'=>'9'],
        'ae' => ['price_column' => 'ae_price', 'currency_code'=>'AED', 'pre_phone' => "+971", 'phone_length'=>'9'],
        'qa' => ['price_column' => 'qa_price', 'currency_code'=>'QAR', 'pre_phone' => "+974", 'phone_length'=>'8'],
        'kw' => ['price_column' => 'kw_price', 'currency_code'=>'KWD', 'pre_phone' => "+965", 'phone_length'=>'8'],
        'bh' => ['price_column' => 'bh_price', 'currency_code'=>'BHD', 'pre_phone' => "+973", 'phone_length'=>'8'],
        'om' => ['price_column' => 'om_price', 'currency_code'=>'OMR', 'pre_phone' => "+968", 'phone_length'=>'8'],
    ];

    public static function ele_maps(){
        return [
            self::NOT_ELE => '不带电',
            self::IS_ELE => '带电',
        ];
    }

    const NOT_IN_LIST = 0;
    const IN_LIST = 1;

    public static function in_list_maps(){
        return [
            self::NOT_IN_LIST => '未添加',
            self::IN_LIST => '已添加',
        ];
    }

    public static function video_position_map(){
        return [1,2,3,4,5,6,7,8,9];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->hasOne(ProductSupplier::class, 'product_id');
    }

    public function setPicturesAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['pictures'] = json_encode($pictures);
        }
    }

    public function getPicturesAttribute($pictures)
    {
        return json_decode($pictures, true);
    }

    public function setWeightAttribute($weight)
    {
        $this->attributes['weight'] = $weight * 1000;
    }

    public function getWeightAttribute($weight)
    {
        return $weight / 1000;
    }

    public function getChildrenAttribute(){
        $children = [];
        foreach ($this->skus as $sku){
            $child = [];
            $child['id'] = $sku?$sku->id:0;
            $child['image'] = $sku?$sku->image:'';
            $child['sku'] = $sku?$sku->sku:'';
            $options = [];
            foreach ($sku->options as $option){
                $child_option = [
                    'option_id' => $option->option_id,
                    'option_name' => $option->option?$option->option->name:'',
                    'option_value' => $option->option_value
                ];
                $options[$option->option_id] = $child_option;
            }
            $child['options'] = $options;
            $children[] = $child;
        }
        return $children;
    }

    public function getFrontChildrenAttribute(){
        $children = [];
        foreach ($this->skus as $sku){
            $child = [];
            $child['id'] = $sku?$sku->id:0;
            $child['sku'] = $sku?$sku->sku:'';
            $child['image'] = $sku?$sku->image:'';
            $options = [];
            foreach ($sku->options as $option){
                $options[] = $option->option_value;
            }
            $child['options'] = join(',', $options);
            $children[] = $child;
        }
        return $children;
    }



    public function options()
    {
        return $this->belongsToMany(Option::class, 'product_options');
    }

    public function skus(){
        return $this->hasMany(Sku::class, 'product_id')
            ->where('skus.status', Sku::STATUS_ONLINE);
    }

    /**
     * 更新活修改数据
     * @param  Product   $product  [description]
     * @param  [type]       $data        [description]
     * @return integer      $id          [description]
     */
    protected function modify(Product $product, $data) : int
    {
        $id = 0;
        $this->valid($data);
        DB::transaction(function () use ($product, $data, &$id) {
            $product->title = $data['title'];
            if(!array_get($data, 'product_no', '')){
                $product->product_no = $product->genProductNo();
            }
            $product->category_id = $data['category_id'];
            $product->is_ele = $data['is_ele'];
            $product->description = $data['description'];
            $product->ocean_number = $data['ocean_number'];
            $product->sa_price = $data['sa_price'];
            $product->ae_price = $data['ae_price'];
            $product->qa_price = $data['qa_price'];
            $product->kw_price = $data['kw_price'];
            $product->bh_price = $data['bh_price'];
            $product->om_price = $data['om_price'];
            $product->video_position = $data['video_position'];
            $product->video_link = $data['video_link']??'';

//            $product->options = $data['options'];
            $product->cost = $data['cost'];
            $product->pictures = $data['pictures'];
            $product->weight = $data['weight'];
            $product->save();
            $product->product_no = $product->genProductNo();
            $product->save();

            if(array_get($data, 'supplier_link', '') || array_get($data, 'supplier_note', '')){
                $supplier_data = array_only($data, ['supplier_link', 'supplier_note']);
                ProductSupplier::modify($product->id, [
                    'link'=>array_get($supplier_data, 'supplier_link', ''),
                    'note'=>array_get($supplier_data, 'supplier_note', ''),
                ]);
            }

            if(array_get($data, 'options', [])){
                $product->options()->sync($data['options']);
            }

            if(array_get($data, 'skus', [])){
                Sku::modify($product->id,$data['skus']);
            }

            $id = $product->id;
        });
        return $id;
    }

    public function valid($data){
        $skus = array_get($data, 'skus', []);
        foreach ($skus as $i => $sku){
            foreach ($sku as $key => $value){
                if(empty($value) && $key != 'options' ){
                    throw new \Exception(sprintf('第 %d 行，%s 值不能为空', $key+1, $key));
                }
            }
            foreach (array_get($sku, 'options', []) as $option){
                if(is_array($option) && empty($option['option_value'])){
                    throw new \Exception(sprintf('第 %d 行，%s 值不能为空', $i+1, $option['option_name']));
                }
            }

        }
    }

    public function genProductNo(){
        if($this->id){
            return Util::getNumber($this->id, Product::NUMBER_PREFIX);
        }
        return uniqid('pn_');
    }
}
