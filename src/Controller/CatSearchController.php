<?php

require_once 'CatController.php';
require_once dirname(__DIR__) . '/Builder/CustomRequest.php';
require_once dirname(__DIR__) . '/Builder/CustomRequestDirector.php';
require_once dirname(__DIR__) . '/Builder/CustomBuilders/CatSearchRequestBuilder.php';

require_once dirname(__DIR__) . '/Adapter/CustomHttpClient.php';
require_once dirname(__DIR__) . '/Adapter/CustomAdapters/CustomHttpClientAdapter.php';
require_once dirname(__DIR__) . '/Adapter/CustomClientInterface.php';

require_once dirname(__DIR__) . '/Factory/CustomHttpClientFactories/GuzzleHttpClientFactory/GuzzleCustomHttpClientFactoryImpl.php';
require_once dirname(__DIR__) . '/Factory/CustomLoggerFactories/FileLoggerFactory/FileCustomLoggerFactoryImpl.php';
require_once dirname(__DIR__) . '/Factory/CustomLoggerFactories/FileLoggerFactory/Logger/FileLogger.php';

class CatSearchController
{
    /**
     * @var CustomClientInterface
     */
    private $client;

    public function __construct(CustomClientInterface $client)
    {
        $this->client = $client;
    }

    public function searchByID($id = '')
    {
        $request = CustomRequestDirector::build(new CatSearchRequestBuilder(new CustomRequest()));
        if ( ! empty($id)) {
            return $this->client->get($request->endpoint . '/' . $id);
        }

        return $this->client->get($request->endpoint);
    }
}

$file_logger = FileCustomLoggerFactoryImpl::createCustomLogger($_SERVER['DOCUMENT_ROOT'] . '/logs/errors.txt');

$redis_cache_client = RedisCustomCacheFactoryImpl::createCustomCacheClient();

$guzzle_client = GuzzleCustomHttpClientFactoryImpl::createCustomHttpClient();
$http_client   = new CustomHttpClient(new CustomHttpClientAdapter($guzzle_client));

$proxy_http_client = new ProxyCustomHttpClientHandler($http_client, $file_logger, $redis_cache_client);

$cat_search_controller = new CatSearchController($proxy_http_client);