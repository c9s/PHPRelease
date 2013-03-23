<?php
namespace PHPRelease\Command;
use CLIFramework\Command;

class InitCommand extends Command
{
    public function brief() { return "initialize phprelease config file"; }

    public function execute()
    {
        $content =<<<END
Steps = PHPUnit, BumpVersion, scripts/compile, GitCommit, GitTag, GitPush, GitPushTags
; VersionFrom = src/PHPRelease/Console.php
END;
        if ( ! file_exists('phprelease.ini') ) {
            file_put_contents('phprelease.ini', $content);
        }
    }
}

