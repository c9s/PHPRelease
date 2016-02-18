<?php
namespace PHPRelease\Tasks;
use PHPRelease\VersionParser;
use PHPRelease\VersionReader;

class GitCommit extends BaseTask
{
    public function options($options)
    {
        $options->add('m|message:', 'commit message.');
    }

    public function execute()
    {
        if ( isset($this->options->message) === true ) {
            $msg = $this->options->message;
        } else {
            $version = $this->getApplication()->getCurrentVersion();
            $msg = "Checking in changes prior to tagging of version $version.";
        }

        passthru("git commit -a -m '$msg'", $retval);
        return $retval == 0;
    }
}


