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

$guzzle_client = GuzzleCustomHttpClientFactoryImpl::createCustomHttpClientWithHandler($redis_cache_client);
$http_client   = new CustomHttpClient(new CustomHttpClientAdapter($guzzle_client));

$proxy_http_client = new ProxyCustomHttpClientHandler($http_client, $file_logger);

$cat_controller = new CatController($proxy_http_client);
