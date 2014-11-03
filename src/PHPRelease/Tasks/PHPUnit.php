<?php
namespace PHPRelease\Tasks;

class PHPUnit extends BaseTask
{
    public function brief() { return "PHPUnit for unit testing"; }

    public function execute()
    {
        $command = array("phpunit");

        if ($this->config('PHPUnit.Debug')) {
            $command[] = '--debug';
        }
        if ($this->config('PHPUnit.Verbose')) {
            $command[] = '--verbose';
        }
        if ($configFile = $this->config('PHPUnit.Configuration')) {
            $command[] = '--configuration';
            $command[] = $configFile;
        }

        passthru(join(' ', $command), $retval);
        return $retval == 0;
    }
}



