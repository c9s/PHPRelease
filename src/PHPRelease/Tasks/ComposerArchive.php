<?php
namespace PHPRelease\Tasks;

class ComposerArchive extends BaseTask
{
    public function brief() { return "Composer archive task"; }

    public function execute()
    {
        $command = array("composer", "archive");
        passthru(join(' ', $command), $retval);
        return $retval == 0;
    }
}



