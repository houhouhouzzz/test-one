<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SkuOption extends Model
{
    protected $fillable = ['sku_id', 'option_id', 'option_value'];
    
    public function option(){
        
        return $this->belongsTo(Option::class);
        
    }


    protected function modify($sku_id, $options){
        if(empty($options)){
            return ;
        }
        $old_option_ids = self::where(compact('sku_id'))->get()->map->id->toArray();
        $new_option_ids = [];
        foreach ($options as $option){
            if(empty($option)) continue;
            $option_obj = self::where(['sku_id'=>$sku_id, 'option_id'=>$option['option_id']])->first();
            if(!$option_obj){
                $option_obj = new self(['sku_id'=>$sku_id, 'option_id'=>$option['option_id']]);
            }
            $option_obj->option_value = array_get($option, 'option_value', '');
            $option_obj->save();
            $new_option_ids[] = $option_obj->id;
        }
        if($diff_ids = array_diff($old_option_ids, $new_option_ids)){
            self::whereIn('id', $diff_ids)->delete();
        }
    }
}
