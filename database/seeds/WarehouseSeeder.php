<?php

use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $base_data = [
            [
                'id' => 1,
                'name' => '国内仓'
            ],
            [
                'id' => 2,
                'name' => '国外仓'
            ],
        ];
        foreach ($base_data as $base_datum){
            $warehouse = \App\Model\Warehouse::find($base_datum['id']);
            if(!$warehouse){
                $warehouse = new \App\Model\Warehouse();
            }
            foreach ($base_datum as $key => $value){
                $warehouse->{$key} = $value;
            }
            $warehouse->save();
        }
        \Illuminate\Support\Facades\Log::info('初始化仓库数据完成');
    }
}
