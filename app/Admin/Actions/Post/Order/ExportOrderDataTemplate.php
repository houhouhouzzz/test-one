<?php

namespace App\Admin\Actions\Post\Order;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class ExportOrderDataTemplate extends Action
{

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-warning" target="_blank" href="/admin/export/template/order_data_template">下载账单模板</a>
HTML;
    }
}