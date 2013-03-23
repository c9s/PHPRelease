<?php
namespace PHPRelease\Tasks;

abstract class BaseTask
{
    public $logger;
    public $config;
    public $options;

    public function __construct($logger, $config, $options = null)
    {
        $this->logger = $logger;
        $this->config = $config;
        if ( $options ) {
            $this->options = $options;
        }
    }

    public function options($options) {  }

    abstract function run();
}


