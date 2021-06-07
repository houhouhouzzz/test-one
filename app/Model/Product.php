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
        'sa' => ['price_column' => 'sa_price', 'currency_code'=>'SAR', 'pre_phone' => "966", 'phone_length'=>'9'],
        'ae' => ['price_column' => 'ae_price', 'currency_code'=>'AED', 'pre_phone' => "971", 'phone_length'=>'9'],
        'qa' => ['price_column' => 'qa_price', 'currency_code'=>'QAR', 'pre_phone' => "974", 'phone_length'=>'8'],
        'kw' => ['price_column' => 'kw_price', 'currency_code'=>'KWD', 'pre_phone' => "965", 'phone_length'=>'8'],
        'bh' => ['price_column' => 'bh_price', 'currency_code'=>'BHD', 'pre_phone' => "973", 'phone_length'=>'8'],
        'om' => ['price_column' => 'om_price', 'currency_code'=>'OMR', 'pre_phone' => "968", 'phone_length'=>'8'],
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

    public function getMainPicturesAttribute(){
        $main_pictures = array_slice($this->pictures, 0, 5);
        array_walk($main_pictures, function (&$main_picture){
            $main_picture = '/static/uploads/' . $main_picture;
        });
        return $main_pictures;
    }

    public function getDetailPicturesAttribute(){
        $detail_pictures = array_slice($this->pictures, 5);
        array_walk($detail_pictures, function (&$detail_picture){
            $detail_picture = '/static/uploads/' . $detail_picture;
        });
        return $detail_pictures;
    }

    public function getSkuPicturesAttribute(){
        return $this->skus->map->image->unique()->values()->toArray();
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
        foreach ($this->skus->groupBy(['main_option_value']) as $skus){
            $options = [];
            $child = [];
            foreach ($skus as $sku){
                if(empty($child['main_option'])){
                    $child['main_option'][] = [
                        'option_id' => $sku->main_option,
                        'option_name' => Option::find($sku->main_option)->name??'',
                        'option_value' => $sku->main_option_value,
                    ];
                }
                $child['image'] = $sku?$sku->image:'';
                foreach ($sku->options as $option){
                    if(empty($options[$option->option_id]['option_name'])){
                        $options[$option->option_id]['option_name'] = $option->option?$option->option->name:'';
                    }
                    if(empty($options[$option->option_id]['option_name'])){
                        $options[$option->option_id]['option_id'] = $option->option_id;
                    }
                    if(empty($options[$option->option_id]['option_value'])){
                        $options[$option->option_id]['option_value'] = [];
                    }
                    if(!in_array($option->option_value, $options[$option->option_id]['option_value'])){
                        $options[$option->option_id]['option_value'][] = $option->option_value;
                    }
                }
            }
            foreach ($options as $key => $tmp_option){
                $options[$key]['option_id'] = $key;
                $options[$key]['option_value'] = join(',', array_get($tmp_option, 'option_value', []) );
            }
            $child['options'] = $options;
            $children[] = $child;
        }
        return $children;
    }

    public function getFrontChildrenAttribute(){
        $children = [];
        $option_names = [];
        $main_options = [];
        $options = [];
        foreach ($this->skus->groupBy(['main_option_value']) as $skus){
            foreach ($skus as $sku){
                if(empty($option_names[$sku->main_option])){
                    $option_names[$sku->main_opion]=Option::find($sku->main_option)->name??'';
                }
                $current_main_option_name =$option_names[$sku->main_opion];
                if(empty($main_options[$current_main_option_name][$sku->main_option_value])){
                    $main_options[$current_main_option_name][$sku->main_option_value] = [
                        'value_name' => $sku->main_option_value,
                        'image' => $sku->image
                    ];
                }
                $child['image'] = $sku?$sku->image:'';
                foreach ($sku->options as $option){
                    if(empty($options[$sku->main_option_value][$option->option_id]['option_name'])){
                        $options[$sku->main_option_value][$option->option_id]['option_name'] = $option->option?$option->option->name:'';
                    }
                    if(empty($options[$sku->main_option_value][$option->option_id]['option_id'])){
                        $options[$sku->main_option_value][$option->option_id]['option_id'] = $option->option_id;
                    }
                    if(empty($options[$sku->main_option_value][$option->option_id]['option_value'])){
                        $options[$sku->main_option_value][$option->option_id]['option_value'] = [];
                    }
                    if(!in_array($option->option_value, $options[$sku->main_option_value][$option->option_id]['option_value'])){
                        $options[$sku->main_option_value][$option->option_id]['option_value'][] = $option->option_value;
                    }
                }
            }
        }
        $children['main_option'] = $main_options;
        $children['options'] = $options;

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
        $this->valid($data, $product);
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
            $product->main_option = $data['main_option'];
            $product->cost = $data['cost'];
            $product->pictures = $data['pictures'];
            $product->weight = $data['weight'];
            $product->product_no = $data['product_no'];
            $product->save();

            if(array_get($data, 'supplier_link', '') || array_get($data, 'supplier_note', '')){
                $supplier_data = array_only($data, ['supplier_link', 'supplier_note']);
                ProductSupplier::modify($product->id, [
                    'link'=>array_get($supplier_data, 'supplier_link', ''),
                    'note'=>array_get($supplier_data, 'supplier_note', ''),
                ]);
            }

            $product->options()->sync(array_get($data, 'options', []));

            if(array_get($data, 'skus', [])){
                Sku::modify($product,$data['skus']);
            }

            $id = $product->id;
        });
        return $id;
    }

    public function valid($data, $product){
        $skus = array_get($data, 'skus', []);
        if(!array_get($data, 'main_option', 0)){
            throw new \Exception('主属性必选');
        }
        $main_options=[];
        foreach ($skus as $i => $sku){
            foreach ($sku as $key => $value){
                if(empty($value) && $key != 'options' ){
                    throw new \Exception(sprintf('第 %d 行，%s 值不能为空', $i+1, $key));
                }
            }
            foreach (array_get($sku, 'main_option', []) as $option){
                if(is_array($option) && empty($option['option_value'])){
                    throw new \Exception(sprintf('第 %d 行，%s(主属性)值不能为空', $i+1, $option['option_name']));
                }
                if(in_array($option['option_value'], $main_options)){
                    throw new \Exception(sprintf('主属性 %s 值重复', $option['option_value']));
                }
                $main_options[] = $option['option_value'];
            }
            foreach (array_get($sku, 'options', []) as $option){
                if(is_array($option) && empty($option['option_value'])){
                    throw new \Exception(sprintf('第 %d 行，%s 值不能为空', $i+1, $option['option_name']));
                }
            }
        }
        $query = self::newQuery();
        if($product->id){
            $query->where('id', '!=', $product->id);
        }
        if($query->where('product_no', array_get($data, 'product_no', ''))->count()){
            throw new \Exception('该货号已存在，请重新输入');
        }

    }

    public function genProductNo(){
        if($this->id){
            return Util::getNumber($this->id, Product::NUMBER_PREFIX);
        }
        return uniqid('pn_');
    }

}
