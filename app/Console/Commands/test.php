<?php

namespace App\Console\Commands;

use App\Extensions\HttpUtil;
use App\Extensions\Util;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $url=sprintf( 'https://www.kuaidi100.com/chaxun?com=%s&nu=%s', 'shentong', '777046161790575');
        $client = new Client();
        $response = $client->request('GET', $url);
//        $res = json_decode($response->getBody(), true);
        dd($response->getBody());
        dd(  $res );

        $result = Util::getNumber(27);
        dd($result);

        $redis = new \Redis();
        $redis->set('eaon', 123);
        $redis->get('eaon');
        dd($redis);

        $info = Util::getInfoByIp('223.73.54.215');
        dd($info);

        $point = '24.75227102275764, 45.47301033551061';
        $points = explode(',', strtr($point, [' '=>''] ));
        $latitude = data_get($points, '0', '0');
        $longitude = data_get($points, '1', '0');
        $url = sprintf( 'https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=%s&longitude=%s&localityLanguage=zh', $latitude, $longitude);
        $response = HttpUtil::getClient()->get($url);
        dd(json_decode($response->getBody(), true));
        $country = Util::getInfoByIp('119.131.210.162');
        dd($country);
    }
}
