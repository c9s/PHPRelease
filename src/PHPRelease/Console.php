<?php
namespace PHPRelease;
use CLIFramework\Application;

class Console extends Application
{

    public function init()
    {
        parent::init();
        $this->registerCommand('bump');
        $this->registerCommand('tag');
        $this->registerCommand('release');
    }

    /*
    public function execute()
    {
        $this->logger->info('here');
    }
    */
}

