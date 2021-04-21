<?php
require_once 'CustomClientInterface.php';
require_once 'CustomHttpClientAdapterInterface.php';

class CustomHttpClient implements CustomClientInterface
{
    /**
     * @var CustomHttpClientAdapterInterface
     */
    private $adapter;

    public function __construct(CustomHttpClientAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function get($uri, $options = [])
    {
        return $this->adapter->get($uri, $options);
    }

    public function post($uri, $options = [])
    {
        return $this->adapter->post($uri, $options);
    }
}