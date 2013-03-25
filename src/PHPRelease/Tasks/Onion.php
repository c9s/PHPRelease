<?php
namespace PHPRelease\Tasks;

class Onion extends BaseTask
{
    public function execute()
    {
        if ( file_exists('package.ini') ) {
            passthru("onion build", $retval);
            return $retval == 0;
        }
    }
}



