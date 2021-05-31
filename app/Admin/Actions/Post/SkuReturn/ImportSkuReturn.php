<?php

namespace App\Admin\Actions\Post\SkuReturn;

use App\Imports\OrderDataImport;
use App\Model\Order;
use App\Model\SkuInventory;
use App\Model\SkuReturn;
use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ImportSkuReturn extends Action
{
    protected $selector = '.import-sku-return';

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

            $header = SkuReturn::SKU_RETURN_EXPORT_HEADER;

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
                $order = Order::where(['tracking_number'=>$row['tracking_number']])->first();
                if(!$order){
                    $error[] = sprintf('第%s行,运单号 tracking_number 不存在,请核对', $key+1, $row['tracking_number']);
                    continue;
                }
                foreach ($order->products as $product){
                    $params = [
                        'sku_id' => $product->sku_id,
                        'origin_tracking_number' => $order->tracking_number,
                    ];
                    $sku_return = SkuReturn::where($params)->first();
                    if(!$sku_return){
                        SkuReturn::create(array_merge($params, [
                            'shipping_method_id' => $order->shipping_method_id,
                            'country_code' => array_flip(Order::ORDER_COUNTRIES)[$order->country]??'',
                        ]));
                        SkuInventory::incrementInventory($product->sku_id, $product->quantity);
                    }
                }
                $order->order_status = Order::ORDER_STATUS_REFUND;
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
        <a class="btn btn-sm btn-default import-sku-return">上传退件运单号</a>
HTML;
    }
}