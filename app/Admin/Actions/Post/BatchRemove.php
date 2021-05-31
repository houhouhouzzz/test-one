<?php

namespace App\Admin\Actions\Post;

use App\Model\Purchase;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BatchRemove extends BatchAction
{
    public $name = '批量删除';

    public function handle(Collection $collection)
    {
        DB::transaction(function () use (&$collection) {
            $purchase_ids = [];
            foreach ($collection as $model) {
                if ($model instanceof Purchase) {
                    if ($model->status == Purchase::STATUS_ARRIVE) {
                        return $this->response()->error('ID 已入库 不能删除');
                    }
                    $purchase_ids[] = $model->id;
                }
            }
            Purchase::whereIn('id', $purchase_ids)->update(['status'=>Purchase::STATUS_DELETE]);
        });

        return $this->response()->success('Success message...')->refresh();
    }

}