<?php

namespace App\Admin\Actions\Post\Purchase;

use App\Model\Purchase;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BatchRemove extends BatchAction
{
    public $name = '批量删除';

    public function handle(Collection $collection)
    {
        $purchase_ids = [];
        foreach ($collection as $model) {
            if ($model instanceof Purchase) {
                if ($model->status == Purchase::STATUS_ARRIVE) {
                    return $this->response()->error( 'Id为' . $model->id . '的采购单 已入库 不能删除');
                }
                $purchase_ids[] = $model->id;
                dd(1);
            }
        }
        Purchase::whereIn('id', $purchase_ids)->update(['status'=>Purchase::STATUS_DELETE]);

        return $this->response()->success('删除成功')->refresh();
    }

}