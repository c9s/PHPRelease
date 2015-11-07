<?php
namespace PHPRelease\Tasks;

class GitTag extends BaseTask
{
    public function brief() { return "Tagging"; }

    public function execute()
    {
        // TODO: generate changelog diff from Changelog file
        $config = $this->getConfig();
        $version = isset($config['GitTagPrefix']) ? $config['GitTagPrefix'] : '';
        $version .= $this->getApplication()->getCurrentVersion();
        $this->logger->info("===> Tagging as $version");
        $lastline = system("git tag \"$version\"", $retval);
        return $retval == 0;
    }
}
