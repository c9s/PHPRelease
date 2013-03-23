<?php
namespace PHPRelease\Tasks;

class GitTag extends BaseTask
{
    public function run()
    {
        // TODO: generate changelog diff from Changelog file
        $version = $this->app->getCurrentVersion();
        $this->logger->info("Tagging $version...");
        $lastline = system("git tag $version", $retval);
        return $retval == 0;
    }
}



