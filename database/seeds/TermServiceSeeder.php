<?php

use Illuminate\Database\Seeder;

class TermServiceSeeder extends Seeder
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
                'name' => 'Shipment',
                'content' => 'Shipment',
            ],
            [
                'id' => 2,
                'name' => 'Returns',
                'content' => 'Returns'
            ],
            [
                'id' => 3,
                'name' =>'About Us',
                'content' =>'About Us',
            ],
            [
                'id' => 4,
                'name' =>'Privacy',
                'content' =>'Privacy',
            ],
            [
                'id' => 5,
                'name' =>'Contact',
                'content' =>'Contact',
            ]
        ];
        foreach ($base_data as $base_datum){
            $term_service = \App\Model\TermService::find($base_datum['id']);
            if(!$term_service){
                $term_service = new \App\Model\TermService();
            }
            foreach ($base_datum as $key => $value){
                $term_service->{$key} = $value;
            }
            $term_service->save();
        }
        \Illuminate\Support\Facades\Log::info('初始化服务条款数据完成');
    }
}
