<?php
namespace PHPRelease\Command;
use CLIFramework\Command;

class BumpCommand extends Command
{
    public function brief() { return "bump version"; }

    public function options($opts) 
    {
        $opts->add('major','bump major version');
        $opts->add('minor','bump minor version');
        $opts->add('s|stability:','set stability');
        foreach( array('dev','rc','beta','alpha','stable') as $s ) {
            $opts->add($s, "set stability to $s.");
        }
    }

    public function execute()
    {
        if ( file_exists('composer.json') ) {
            // trying to read the version
        }
    }
}

