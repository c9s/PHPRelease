<?php
namespace PHPRelease\Tasks;
use CLIFramework\Command;

abstract class BaseTask extends Command
{
    public $config;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function options($options) {  }
}


