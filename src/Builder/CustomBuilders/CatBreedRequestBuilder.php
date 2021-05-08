<?php

require_once dirname(__DIR__) . '/CustomRequestBuilderInterface.php';
require_once dirname(__DIR__) . '/CustomRequest.php';

class CatBreedRequestBuilder implements CustomRequestBuilderInterface
{
    /**
     * @var CustomRequest
     */
    private $customRequest;

    public function __construct($customRequest)
    {
        $this->customRequest = $customRequest;
    }

    public function setEndpoint()
    {
        $this->customRequest->endpoint .= 'breeds';
    }

    public function setLimit($limit)
    {
        $this->customRequest->endpoint .= '?limit=' . $limit;
    }

    public function getCustomRequest()
    {
        return $this->customRequest;
    }
}