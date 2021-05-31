<?php

namespace App\Admin\Actions\Post;

use App\Model\Purchase;
use App\Model\SkuInventory;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BatchReceive extends BatchAction
{
    public $name = '批量入库';

    public function handle(Collection $collection)
    {
        foreach ($collection as $model) {
            if($model->status != Purchase::STATUS_ARRIVE){
                DB::transaction(function () use($model) {
                    $model->status = Purchase::STATUS_ARRIVE;
                    $model->save();
                    SkuInventory::incrementInventory($model->sku_id, $model->quantity);
                });
            }
        }

        return $this->response()->success('Success message...')->refresh();
    }

}