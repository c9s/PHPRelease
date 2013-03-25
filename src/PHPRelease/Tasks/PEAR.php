<?php
namespace PHPRelease\Tasks;

class PEAR extends BaseTask
{
    public function execute()
    {
        passthru("pear package", $retval);
        return $retval == 0;
    }
}



