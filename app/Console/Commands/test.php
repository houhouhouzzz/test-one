<?php

namespace App\Console\Commands;

use App\Extensions\HttpUtil;
use App\Extensions\Util;
use App\Model\Image;
use App\Model\Order;
use App\Model\Product;
use App\Model\Sku;
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

        $skus = Sku::all();
        foreach ($skus as $sku){
            $sku->image = strtr($sku->image, ['static/uploads/' => '']);
            $sku->save();
        }

        $images = Image::all();
        foreach ($images as $image){
            $image->path = strtr($image->path, ['static/uploads/' => '']);
            $image->save();
        }

        $products = Product::all();
        foreach ($products as $product){
            $pics = [];
            foreach( $product->pictures as $picture ){
                $pics[] = strtr($picture, ['static/uploads/' => '']);
            }
            $product->pictures = $pics;
            $product->save();
        }
        dd(1);

        $order = Order::find(10018);

        $product_desc = $product_weight = $ocean_code = $product_quantity = $product_cost = $product_sku =[];
        $order->products->map(function ($product)use(&$product_desc, &$product_weight, &$ocean_code, &$product_quantity, &$product_cost, &$product_sku){
            $product_desc[] = $product->product->description;
            $product_weight[] = $product->product->weight;
            $ocean_code[] = $product->product->ocean_number;
            $product_quantity[] = $product->quantity;
            $product_cost[] = $product->product->cost / 2 * 6.5;
            $product_sku[] = $product->sku->sku;
        });

        dd([$product_desc, $product_weight, $product_quantity, $product_cost, $product_sku, $ocean_code]);
        $order->products->product->map->description;

        logger(file_get_contents('https://www.jq22.com/demo/jquerylbt202012090028/js/lbt.js'));
dd(file_get_contents('https://www.jq22.com/demo/jquerylbt202012090028/js/lbt.js'));
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
