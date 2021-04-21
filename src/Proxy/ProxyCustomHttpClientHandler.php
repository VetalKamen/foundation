<?php

use GuzzleHttp\Promise\FulfilledPromise;

require_once dirname(__DIR__) . '/Adapter/CustomClientInterface.php';
require_once dirname(__DIR__) . '/Adapter/CustomHttpClient.php';
require_once dirname(dirname(__DIR__)) . '/functions.php';
require_once dirname(__DIR__) . '/Factory/CustomLoggerFactories/FileLoggerFactory/Logger/FileLogger.php';

class ProxyCustomHttpClientHandler implements CustomClientInterface
{
    /**
     * @var CustomClientInterface
     */
    private $realClient;

    /**
     * @var CustomLoggerInterface
     */
    private $fileLogger;

    private $cacheClient;

    public function __construct(
        CustomClientInterface $realClient,
        CustomLoggerInterface $logger,
        $cacheClient
    ) {
        $this->realClient  = $realClient;
        $this->fileLogger  = $logger;
        $this->cacheClient = $cacheClient;
    }

    public function get(string $uri, array $options = [])
    {
        try {
            return $this->realClient->get($uri, $options);
        } catch (Exception $e) {
            $this->fileLogger->writeLogs('CustomHttpClient exception in the Adapter::GET| Message:' . $e->getMessage());
        }

    }

    public function post(string $uri, array $options = [])
    {
        try {
            return $this->realClient->post($uri, $options);
        } catch (Exception $e) {
            $this->fileLogger->writeLogs('CustomHttpClient exception in the Adapter::POST| Message:' . $e->getMessage());
        }
    }
}