<?php


require_once dirname(__DIR__) . '/CustomHttpClientAdapterInterface.php';
require_once dirname(dirname(dirname(__DIR__))) . '/functions.php';

class CustomHttpClientAdapter implements CustomHttpClientAdapterInterface
{
    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function get($uri, $options = [])
    {
        return $this->client->get($uri, $options);
    }

    public function post($uri, $options = [])
    {

        return $this->client->post($uri, $options);

    }
}