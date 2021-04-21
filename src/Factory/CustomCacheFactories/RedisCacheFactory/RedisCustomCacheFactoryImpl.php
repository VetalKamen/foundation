<?php

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/functions.php';
require_once dirname(dirname(__DIR__)) . '/CustomCacheFactoryInterface.php';

class RedisCustomCacheFactoryImpl implements CustomCacheFactoryInterface
{

    public static function createCustomCacheClient()
    {
        return new \Predis\Client([
            "scheme" => "tcp",
            "host"   => REDIS_HOST,
            "port"   => REDIS_PORT,
        ]);
    }
}