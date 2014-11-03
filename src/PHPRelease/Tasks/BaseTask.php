<?php
namespace PHPRelease\Tasks;
use CLIFramework\Command;
use ConfigKit\Accessor;

abstract class BaseTask extends Command
{
    /**
     * @var array The task config
     */
    public $config;

    protected $configAccessor;

    public function setConfig(array $config)
    {
        $this->config = $config;
        $this->configAccessor = new Accessor($this->config);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function config($key) {
        return $this->configAccessor->lookup($key);
    }

    public function options($options) {  }
}


