<?php
namespace PHPRelease\Tasks;


abstract class BaseTask
{
    public $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    abstract function run();
}


