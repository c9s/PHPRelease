<?php
namespace PHPRelease\Tasks;

class GitTag extends BaseTask
{
    public function execute()
    {
        // TODO: generate changelog diff from Changelog file
        $version = $this->application->getCurrentVersion();
        $this->logger->info("Tagging $version...");
        $lastline = system("git tag $version", $retval);
        return $retval == 0;
    }
}



