<?php


namespace App\Extensions;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class HttpUtil
{
    private static $client;

    const MAX_RETRIES = 5;
    const TIMEOUT = 60;
    const CONNECT_TIMEOUT = 30;

    public static function getClient ()
    {
        if (is_null(self::$client)) {
            //添加失败重试中间件
            $handlerStack = HandlerStack::create(new CurlHandler());
            $handlerStack->push(Middleware::retry(self::retryDecider(), self::retryDelay()));
            self::$client = new Client([
                'handler' => $handlerStack,
                'timeout' => self::TIMEOUT,
                'connect_timeout' => self::CONNECT_TIMEOUT,
            ]);
        }

        return self::$client;
    }

    /**
     * retry decider
     * 重试决策
     * @return \Closure
     */
    protected static function retryDecider()
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            if ($retries >= self::MAX_RETRIES) {
                return false;
            }

            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response) {
                if ($response->getStatusCode() >= 429) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * retry delay
     * 重试延迟时间
     * @return \Closure
     */
    protected static function retryDelay()
    {
        return function ($numberOfRetries) {
            return 1000 * $numberOfRetries;
        };
    }
}