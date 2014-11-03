<?php
namespace PHPRelease\Tasks;

class GitTag extends BaseTask
{
    public function brief() { return "Tagging"; }

    public function execute()
    {
        // TODO: generate changelog diff from Changelog file
        $version = $this->getApplication()->getCurrentVersion();
        $this->logger->info("Tagging $version...");
        $lastline = system("git tag $version", $retval);
        return $retval == 0;
    }
}



