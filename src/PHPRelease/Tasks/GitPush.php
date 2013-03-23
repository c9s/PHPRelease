<?php
namespace PHPRelease\Tasks;

class GitPush extends BaseTask
{
    public function options($options)
    {
        $options->add('remote+','git remote names for pushing.');
    }

    public function execute()
    {
        $branch = system('git rev-parse --abbrev-ref HEAD');
        $remotes = array('origin');
        if ( $this->options->remote && in_array('all',$this->options->remote) ) {
            $remotes = explode("\n",trim(shell_exec('git remote')));
        } elseif ( $this->options->remote ) {
            $remotes = $this->options->remote;
        }
        foreach ( $remotes as $remote ) {
            $this->logger->info("---> Pushing to remote $remote...");
            passthru("git push $remote $branch", $retval);
            if ( $retval != 0 )
                return false;
        }
        return true;
    }
}


