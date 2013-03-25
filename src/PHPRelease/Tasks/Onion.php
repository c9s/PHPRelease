<?php
namespace PHPRelease\Tasks;

class Onion extends BaseTask
{
    public function execute()
    {
        passthru("onion build", $retval);
        return $retval == 0;
    }
}



