<?php

interface CustomRequestBuilderInterface
{
    public function setEndpoint();

    public function setLimit($limit);

    public function getCustomRequest();
}