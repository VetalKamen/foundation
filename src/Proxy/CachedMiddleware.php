<?php

use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\{
    RequestInterface, ResponseInterface
};

class CachedMiddleware
{
    public const CACHE_TIME_IN_S = 'cache_time';
    private const DEFAULT_CACHE_TIME = 5;
    private $cache;

    public function __construct($cacheClient)
    {
        $this->cache = $cacheClient;
    }

    public function onRequest(int $defaultCacheTime = self::DEFAULT_CACHE_TIME)
    {
        return function (callable $handler) use ($defaultCacheTime) {
            return function (RequestInterface $request, array $options) use ($handler, $defaultCacheTime) {

                $cacheKey = sha1((string)$request->getUri() . implode($options));

                if ($cachedResponse = $this->cache->get($cacheKey)) {
                    return new FulfilledPromise($cachedResponse);
                }

                $cacheTime = $options[self::CACHE_TIME_IN_S] ?? $defaultCacheTime;

                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($request, $cacheKey, $cacheTime) {

                        $this->cache->set($cacheKey, $response, $cacheTime);

                        return $response;
                    },
                    function (TransferException $e) {
                        return new RejectedPromise($e);
                    }
                );
            };
        };
    }
}