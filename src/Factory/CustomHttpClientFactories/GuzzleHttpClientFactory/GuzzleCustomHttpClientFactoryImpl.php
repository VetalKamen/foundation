<?php

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/functions.php';
require_once dirname(dirname(__DIR__)) . '/CustomHttpClientFactoryInterface.php';

use GuzzleHttp\HandlerStack;

class GuzzleCustomHttpClientFactoryImpl implements CustomHttpClientFactoryInterface
{
    public static function createCustomHttpClientWithHandler($cacheClient)
    {
        if ( ! empty($cacheClient)) {
            $stack = HandlerStack::create();
            $stack->push(guzzle_cache_middleware($cacheClient));
        }

        return new GuzzleHttp\Client([
            'base_uri' => THE_CAT_API_ENDPOINT,
            'handler'  => $stack ?? '',
            'headers'  => [
                'Accept'    => 'application/json',
                'x-api-key' => THE_CAT_API_KEY,
            ]
        ]);
    }

    public static function createCustomHttpClient()
    {
        return new GuzzleHttp\Client([
            'base_uri' => THE_CAT_API_ENDPOINT,
            'headers'  => [
                'Accept'    => 'application/json',
                'x-api-key' => THE_CAT_API_KEY,
            ]
        ]);
    }
}