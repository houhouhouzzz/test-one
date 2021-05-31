<?php

namespace App\Admin\Actions\Post;

use App\Model\Product;
use App\Model\Sku;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Replicate extends RowAction
{
    public $name = '复制';

    public function handle(Model $model)
    {
        if($model instanceof Product){
            DB::transaction(function () use($model) {
                $new_model = $model->replicate();
                $new_model->product_no = $new_model->genProductNo();
                $new_model->save();
                $new_model->product_no = $new_model->genProductNo();
                $new_model->save();
                foreach ($model->skus as $sku){
                    /**
                     * @var Sku $new_sku
                     */
                    $new_sku = $sku->replicate();
                    $new_sku->product_id = $new_model->id;
                    $new_sku->save();
                    $new_sku->sku = $new_sku->genSku();
                    $new_sku->save();
                    $new_sku_options = [];
                    foreach ($sku->options as $option){
                        $new_sku_options[] = $option->replicate();
                    }
                    $new_sku->options()->savemany($new_sku_options);
                }
                $new_options = $model->options->map->id;
                $new_model->options()->sync($new_options);
                $new_model->supplier()->save($model->supplier->replicate());
            });
            return $this->response()->success('商品复制成功')->refresh();
        }



        return $this->response()->error('该类型不存在')->refresh();
    }

}