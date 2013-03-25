<?php
namespace PHPRelease\Tasks;
use PHPRelease\VersionParser;
use PHPRelease\VersionReader;


/**
 * Put the config to your phprelease.ini:
 *
 * [GitAdd]
 * Paths[] = src
 * Paths[] = tests
 *
 */

class GitAdd extends BaseTask
{
    public function execute()
    {
        $config = $this->application->getConfig();
        if ( isset($config['GitAdd']['Paths']) ) {
            foreach( $config['GitAdd']['Paths'] as $path ) {
                passthru("git add -v ",$retval);
                if ($retval != 0) {
                    return false;
                }
            }
        }
        return true;
    }
}


