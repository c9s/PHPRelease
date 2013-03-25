<?php
namespace PHPRelease\Tasks;

class Pyrus extends BaseTask
{
    public function execute()
    {
        passthru("pyrus package", $retval);
        return $retval == 0;
    }
}



