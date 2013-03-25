<?php
namespace PHPRelease\Tasks;

class PHPUnit extends BaseTask
{
    public function brief() { return "PHPUnit for unit testing"; }

    public function execute()
    {
        passthru("phpunit", $retval);
        return $retval == 0;
    }
}



