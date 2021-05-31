<?php

namespace App\Admin\Actions\Post\Order;

use App\Model\Order;
use App\Model\Purchase;
use App\Model\ShippingMethod;
use App\Model\SkuInventory;
use App\Model\Warehouse;
use Carbon\Carbon;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BatchDefaultShipping extends BatchAction
{
    public $name = '一键发货合联';

    public function handle(Collection $collection)
    {
        foreach ($collection as $model) {

        }

        return $this->response()->success('订单状态变更成功')->refresh();
    }

    public function render()
    {

    }
}