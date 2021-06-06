<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gift extends Model
{
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;

    public static $status_maps =[
        self::STATUS_OPEN => '开启',
        self::STATUS_CLOSE => '关闭'
    ];

    public function main_product()
    {
        return $this->belongsTo(Product::class, 'main_product_id');
    }

    public function gift_product()
    {
        return $this->belongsTo(Product::class, 'gift_product_id');
    }

    /**
     * 更新或修改数据
     * @param  Gift   $gift  [description]
     * @param  [type]       $data        [description]
     * @return integer      $id          [description]
     */
    protected function modify(Gift $gift, $data) : int
    {
        $id = 0;
        $data = $this->valid($data, $gift);
        DB::transaction(function () use ($gift, $data, &$id) {
            $gift->title = $data['title'];
            $gift->main_product_id = $data['main_product_id'];
            $gift->gift_product_id = $data['gift_product_id'];
            $gift->status = $data['status'];
            $gift->save();
        });
        return $id;
    }

    public function valid($data, $gift){
        $main_product_no = array_get($data, 'main_product_no', '');
        $gift_product_no = array_get($data, 'gift_product_no', '');
        if(!$main_product_no){
            throw new \Exception('主商品(货号)未输入');
        }
        if(!$gift_product_no){
            throw new \Exception('赠品(货号)未输入');
        }
        if(!$main_product = Product::whereProductNo($main_product_no)->first()){
            throw new \Exception('主商品(货号)不存在，请重新输入');
        }
        if( !$gift_product = Product::whereProductNo($gift_product_no)->first()){
            throw new \Exception('赠品(货号)不存在，请重新输入');
        }
        $query = Gift::query();
        if( $id = $gift->id){
            $query->where('id', '!=', $id);
        }
        $query->where('main_product_id', $main_product->id);
        if($query->count()){
            throw new \Exception('主商品已有相应赠品，请重新输入');
        }

        return [
            'title' => array_get($data, 'title', ''),
            'main_product_id' => $main_product->id,
            'gift_product_id' => $gift_product->id,
            'status' => array_get($data, 'status', 0)
        ];
    }
}
