<?php

require_once 'Logger/FileLogger.php';
require_once dirname(dirname(__DIR__)) . '/CustomLoggerFactoryInterface.php';

class FileCustomLoggerFactoryImpl implements CustomLoggerFactoryInterface
{
    public static function createCustomLogger($filePath)
    {
        return new FileLogger($filePath);
    }
}
