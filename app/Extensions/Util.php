<?php


namespace App\Extensions;



use App\Model\Product;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Util
{
    public static function getIp(){
        return $_SERVER['HTTP_X_FORWARDED_FOR']??$_SERVER['REMOTE_ADDR'];
    }

    public static function getCountry(){
        $country = 'sa';
        $user_country = strtolower(self::getRequestCountry());
        if(in_array( $user_country, array_keys(Product::PRICE_COLUMNS))){
            $country = $user_country;
        }
        return $country;
    }

    public static function getRequestCountry(){
        $ip = request()->getClientIp();
        return Util::getInfoByIp($ip, 'iso_code');
    }

    public static function getInfoByIp($ip, $info=''){
        $location = \GeoIP::getLocation($ip);
        if($info){
            return $location->{$info};
        }
        return $location->toArray();
    }

    /**
     * @param       $path
     * @param array $parameters
     * @param null  $root_url
     * @return mixed
     */
    public static function to($path, $parameters = [])
    {
        $server = request()->server();
        $domain = $server['REQUEST_SCHEME'] . '://' . $server['HTTP_HOST'];
        $path = rtrim($domain, '\/') . '/' . ltrim($path, '/');

        $code = "en";
        if (!preg_match("/^en/", $code)) {
            $domain = strtr($domain, ['://www.' => '://' . $code . '.']);
        }
        $secure = Str::startsWith($domain, 'https://');
        return URL::to($path, $parameters, $secure);
    }

    public static function jumpToProduct($path, $parameters = []){
        $default_iso_code = Util::getCountry();
        $path = rtrim($path, '/') . '/' . $default_iso_code;
        $server = request()->server();
        $domain = $server['REQUEST_SCHEME'] . '://' . $server['HTTP_HOST'];
        $path = rtrim($domain, '\/') . '/' . ltrim($path, '/');

        $code = "en";
        if (!preg_match("/^en/", $code)) {
            $domain = strtr($domain, ['://www.' => '://' . $code . '.']);
        }
        $secure = Str::startsWith($domain, 'https://');
        return URL::to($path, $parameters, $secure);
    }

    public static function currencyFormat($money, $currency = 'SAR'){
        $fmt =  new \NumberFormatter($currency, \NumberFormatter::CURRENCY);;
        $price = $fmt->formatCurrency(round(floatval($money), 2), $currency);
        $rtn = [
            'price' => $price,
            'price_value' => $money,
            'value' => $money . '',
            'code' => $currency,
        ];
        return $rtn;
    }

    public static function getNumber(int $id, $prefix = '', $size = 5){
        $id--;
        if($id > pow(36 ,$size) ){
            $size = $size + 1;
            return self::getNumber($id, $prefix, $size);
        }
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $number = '';
        for($i=1;$i < $size; $i++){
            $number = substr($str
                ,floor($id / pow(36 ,$i-1)) % pow(36 ,$i)
                , 1) . $number;
        }
        return $prefix . $number;
    }

}
