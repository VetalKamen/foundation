<?php

require_once 'CustomRequestBuilderInterface.php';

class CustomRequestDirector
{
    public static function build(CustomRequestBuilderInterface $builder, $limit = 5)
    {
        $builder->setEndpoint();
        $builder->setLimit($limit);

        return $builder->getCustomRequest();
    }
}