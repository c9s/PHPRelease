<?php
namespace PHPRelease\Tasks;

class GitPush extends BaseTask
{
    public function options($options)
    {
    }

    public function execute()
    {
        $branch = system('git rev-parse --abbrev-ref HEAD');
        passthru("git push origin $branch", $retval);
        return $retval == 0;
    }
}


