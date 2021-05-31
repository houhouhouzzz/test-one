<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class ViewDetail extends RowAction
{
    public $name = '查看详情';

    public function handle(Model $model)
    {
        // $model ...

        return $this->response()->refresh();
    }

    public function form()
    {

        $this->textarea('reason', '内容')->attribute('style="height:230px"')->value('Hi, dear customer,
The order you made online, please confirm:
Product: as pic shows
Delivery time：About 10-15 days
Total amount: XXX free delivery
Receiver:
Location:
Phone:
Delivery: Cash on delivery
If you have any questions, please feel free to contact me.
Thank you.');
    }

}