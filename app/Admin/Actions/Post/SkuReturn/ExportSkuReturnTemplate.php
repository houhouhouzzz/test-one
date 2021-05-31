<?php

namespace App\Admin\Actions\Post\SkuReturn;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class ExportSkuReturnTemplate extends Action
{

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-warning" target="_blank" href="/admin/export/template/sku_refund_template">下载退件模板</a>
HTML;
    }
}