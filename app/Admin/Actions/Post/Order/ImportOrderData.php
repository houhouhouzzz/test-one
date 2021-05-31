<?php

namespace App\Admin\Actions\Post\Order;

use App\Imports\OrderDataImport;
use App\Model\Order;
use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use mysql_xdevapi\Exception;

class ImportOrderData extends Action
{
    protected $selector = '.import-order-data';

    public function handle(Request $request)
    {
        // 下面的代码获取到上传的文件，然后使用`maatwebsite/excel`等包来处理上传你的文件，保存到数据库
        $file = $request->file('file');
        try {
            $collection = \Maatwebsite\Excel\Facades\Excel::toCollection(new OrderDataImport(), $file->getRealPath(), null, Excel::XLSX)->shift();

            if (!isset($collection)) {
                return false;
            }

            $excelHeader = $collection->shift();

            $header = Order::ORDER_EXPORT_HEADER;

            $headerToFields = array_keys($header);

            if (array_diff($header, $excelHeader->toArray())) {
                throw new \Exception( '表头不一致；正确表头:'
                    . implode(',', $header) . '；' . '当前表头：' . implode(',', $excelHeader->toArray()));
            }

            $limit = 5000;
            if ($collection->count() > $limit) {
                throw new \Exception("一次最多导入{$limit}条");
            }

            $rows = [];
            foreach ($collection as $index => $value) {
                $value = $value->toArray();

                // 跳过全部字段空的行
                if (count(array_filter($value)) == 0) {
                    continue;
                }

                $fields = [];
                foreach ($value as $key => $val) {
                    if (!isset($headerToFields[$key])) {
                        continue;
                    }
                    $fields[$headerToFields[$key]] = trim($val);
                }

                $rows[$index] = $fields;
            }
            $error = [];
            foreach ($rows as $key => $row){
                $order = Order::where(['order_no'=>$row['order_no']])->first();
                if(!$order){
                    $error[] = sprintf('第%s行,订单 %s 不存在,请核对', $key+1, $row['order_no']);
                    continue;
                }
                foreach ($row as $row_key => $item){
                    if(!empty($item)){
                        $order->{$row_key} = $item;
                    }
                }
                $order->save();
            };
            if($error){
                throw new \Exception(join('<br>', $error));
            }
        }catch (\Exception $e){
            return $this->response()->error($e->getMessage());
        }

        return $this->response()->success('导入完成！')->refresh();
    }

    public function form()
    {
        $this->file('file', '请选择文件')->attribute('accept=".xls,.xlsx"')->rules('required',
            [
                'required' => '文件必选',
            ]
        );
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-danger import-order-data">上传账单</a>
HTML;
    }
}