<?php
namespace PHPRelease\Tasks;

abstract class BaseTask
{
    public $app;
    public $logger;
    public $config;
    public $options;

    public function __construct($app, $logger, $config, $options = null)
    {
        $this->app = $app;
        $this->logger = $logger;
        $this->config = $config;
        if ( $options ) {
            $this->options = $options;
        }
    }

    public function options($options) {  }

    abstract function run();
}


