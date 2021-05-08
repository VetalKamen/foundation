<?php

require_once dirname(dirname(__DIR__)) . '/CustomLoggerInterface.php';

class FileLogger implements CustomLoggerInterface
{
    private $pathToWrite;

    public function __construct($filePath)
    {
        $this->pathToWrite = $filePath;
    }

    public function writeLogs($content)
    {
        $content .= '| Report has been generated at:' . date("Y-m-d H:i:s") . "\r\n";

        return file_put_contents($this->pathToWrite, $content, FILE_APPEND | LOCK_EX);
    }
}