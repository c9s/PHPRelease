<?php
namespace PHPRelease\Tasks;

class GitPushTags extends BaseTask
{
    public function options($options)
    {
    }

    public function execute()
    {
        passthru("git push origin --tags", $retval);
        return $retval == 0;
    }
}


