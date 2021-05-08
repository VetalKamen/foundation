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

    public function default_search($breed_id = '',$category_id = '', $filetype = '', $page = '')
    {
        $request = CustomRequestDirector::build(new CatSearchRequestBuilder(new CustomRequest()));
        $request->set_param_to_query('breed_ids', $breed_id);
        $request->set_param_to_query('category_ids', $category_id);
        $request->set_param_to_query('mime_types', $filetype);
        $request->set_param_to_query('page', $page);

        return $this->client->get($request->endpoint);
    }
}

$file_logger = FileCustomLoggerFactoryImpl::createCustomLogger($_SERVER['DOCUMENT_ROOT'] . '/logs/errors.txt');

$redis_cache_client = RedisCustomCacheFactoryImpl::createCustomCacheClient();

$guzzle_client = GuzzleCustomHttpClientFactoryImpl::createCustomHttpClientWithHandler($redis_cache_client);
$http_client   = new CustomHttpClient(new CustomHttpClientAdapter($guzzle_client));

$proxy_http_client = new ProxyCustomHttpClientHandler($http_client, $file_logger);

$cat_search_controller = new CatSearchController($proxy_http_client);