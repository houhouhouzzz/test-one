<?php

namespace App\Admin\Controllers;

use App\Exports\OrderDataTemplateExport;
use App\Exports\SkuReturnTemplateExport;
use Encore\Admin\Controllers\AdminController;
use Maatwebsite\Excel\Excel;

class ExportController extends AdminController
{
    public function template($name){
        if($name == 'order_data_template'){
            return app(Excel::class)->download(new OrderDataTemplateExport, 'order_data.xlsx');
        }
        if($name == 'sku_refund_template'){
            return app(Excel::class)->download(new SkuReturnTemplateExport, 'sku_return.xlsx');
        }
        return [];
    }
}
