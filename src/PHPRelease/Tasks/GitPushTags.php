<?php
namespace PHPRelease\Tasks;

class GitPushTags extends BaseTask
{

    public function options($options)
    {
        $options->add('remote+','git remote names for pushing.');
    }

    public function execute()
    {
        $remotes = array('origin');
        if ( $this->options->remote && in_array('all',$this->options->remote) ) {
            $remotes = explode("\n",trim(shell_exec('git remote')));
        } elseif ( $this->options->remote ) {
            $remotes = $this->options->remote;
        }
        foreach ( $remotes as $remote ) {
            passthru("git push $remote --tags", $retval);
            if ( $retval != 0 )
                return false;
        }
        return true;
    }
}


