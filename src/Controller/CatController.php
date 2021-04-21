<?php

require_once dirname(__DIR__) . '/Builder/CustomRequest.php';
require_once dirname(__DIR__) . '/Builder/CustomRequestDirector.php';
require_once dirname(__DIR__) . '/Builder/CustomBuilders/CatBreedRequestBuilder.php';
require_once dirname(__DIR__) . '/Builder/CustomBuilders/CatCategoryRequestBuilder.php';

require_once dirname(__DIR__) . '/Adapter/CustomHttpClient.php';
require_once dirname(__DIR__) . '/Adapter/CustomClientInterface.php';
require_once dirname(__DIR__) . '/Adapter/CustomAdapters/CustomHttpClientAdapter.php';

require_once dirname(__DIR__) . '/Factory/CustomHttpClientFactories/GuzzleHttpClientFactory/GuzzleCustomHttpClientFactoryImpl.php';
require_once dirname(__DIR__) . '/Factory/CustomLoggerFactories/FileLoggerFactory/FileCustomLoggerFactoryImpl.php';
require_once dirname(__DIR__) . '/Factory/CustomLoggerFactories/FileLoggerFactory/Logger/FileLogger.php';
require_once dirname(__DIR__) . '/Factory/CustomCacheFactories/RedisCacheFactory/RedisCustomCacheFactoryImpl.php';

require_once dirname(__DIR__) . '/Proxy/ProxyCustomHttpClientHandler.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Psr7;

class CatController
{
    /**
     * @var CustomClientInterface
     */
    private $client;

    public function __construct(CustomClientInterface $client)
    {
        $this->client = $client;
    }

    public function listBreeds()
    {
        $request = CustomRequestDirector::build(new CatBreedRequestBuilder(new CustomRequest()));

        return $this->client->get($request->endpoint);
    }

    public function listCategories()
    {
        $request = CustomRequestDirector::build(new CatCategoryRequestBuilder(new CustomRequest()));

        return $this->client->get($request->endpoint);
    }
}


$file_logger = FileCustomLoggerFactoryImpl::createCustomLogger($_SERVER['DOCUMENT_ROOT'] . '/logs/errors.txt');

$redis_cache_client = RedisCustomCacheFactoryImpl::createCustomCacheClient();

function add_response_handler($cacheClient)
{
    return function (callable $handler) use ($cacheClient) {
        return function (
            RequestInterface $request,
            array $options
        ) use ($handler, $cacheClient) {
            $cacheKey = sha1((string)$request->getUri());

            if ($cachedResponse = unserialize($cacheClient->get($cacheKey))) {

                return new FulfilledPromise($cachedResponse);
            }
            $promise = $handler($request, $options);

            return $promise->then(
                function (ResponseInterface $response) use ($cacheKey, $cacheClient) {
                    $cacheClient->set($cacheKey, serialize($response));

                    return $response;
                }
            );
        };
    };
}

$stack = HandlerStack::create();
$stack->setHandler(new CurlHandler());
$stack->push(add_response_handler($redis_cache_client));


$guzzle_client = GuzzleCustomHttpClientFactoryImpl::createCustomHttpClient($stack);
$http_client   = new CustomHttpClient(new CustomHttpClientAdapter($guzzle_client));

$proxy_http_client = new ProxyCustomHttpClientHandler($http_client, $file_logger, $redis_cache_client);

$cat_controller = new CatController($proxy_http_client);

