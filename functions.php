<?php

use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

require_once 'vendor/autoload.php';

/**
 * TheCatAPI endpoint and API key.
 */
const THE_CAT_API_ENDPOINT = 'https://api.thecatapi.com/v1/';
const THE_CAT_API_KEY      = 'b199af59-f7cb-4307-95ed-7b7f934c16ed';

/**
 * Redis connection configs.
 */
const REDIS_HOST = "127.0.0.1";// default: 127.0.0.1
const REDIS_PORT = 6379;// default: 6379

function guzzle_cache_middleware($cacheClient)
{
    return Middleware::tap(
        null,
        function ($request, $options, PromiseInterface $promise) use ($cacheClient) {
            return $promise->then(
                function (ResponseInterface $response) use ($cacheClient, $request) {
                    $cacheKey = sha1((string)$request->getUri());

                    if ($cachedResponse = unserialize($cacheClient->get($cacheKey))) {

                        return new FulfilledPromise($cachedResponse);
                    }
                    $cacheClient->set($cacheKey, serialize($response));

                    return $response;
                }
            );
        }
    );
}
