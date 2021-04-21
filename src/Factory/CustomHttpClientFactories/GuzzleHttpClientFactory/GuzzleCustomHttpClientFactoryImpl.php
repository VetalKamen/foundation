<?php

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/functions.php';
require_once dirname(dirname(__DIR__)) . '/CustomHttpClientFactoryInterface.php';

class GuzzleCustomHttpClientFactoryImpl implements CustomHttpClientFactoryInterface
{
    public static function createCustomHttpClient($handler)
    {
        return new GuzzleHttp\Client([
            'base_uri' => THE_CAT_API_ENDPOINT,
            'handler'  => $handler,
            'headers'  => [
                'Accept'    => 'application/json',
                'x-api-key' => THE_CAT_API_KEY,
            ]
        ]);
    }
}