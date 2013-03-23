<?php
namespace PHPRelease;
use CLIFramework\Application;
use RuntimeException;

class Console extends Application
{
    public function init()
    {
        parent::init();
        $this->registerCommand('bump');
        $this->registerCommand('tag');
        $this->registerCommand('release');
    }

    public function options($opts)
    {
        $opts->add('dry','dryrun mode.');
    }

    public function runSteps($steps, $dryrun = false)
    {
        foreach( $steps as $step ) {
            if ( file_exists( $step ) ) {
                if ( ! is_executable($step) ) {
                    throw new RuntimeException("$step is not an executable.");
                }
                $this->logger->info("===> Running $step");
                if ( ! $dryrun ) {
                    $lastline = system($step, $retval);
                    if ( $retval ) {
                        $this->logger->error($lastline);
                        $this->logger->error("===> $step failed, aborting...");
                        exit(0);
                    }
                }
            }

            $task = null;
            if ( class_exists( $step, true ) ) {
                $task = new $step( $this->logger );
            } else {
                // built-in task
                $taskClass = 'PHPRelease\\Tasks\\' . $step;
                if ( class_exists($taskClass, true ) ) {
                    $task = new $step( $this->logger );
                }
            }
            if ( ! $task ) {
                $this->logger->error("===> Taks $step not found, aborting...");
                exit(0);
            }

            $this->logger->info("===> Running " . get_class($task) );
            if ( ! $dryrun ) {
                $retval = $task->run();
                if ( false === $retval ) {
                    $this->logger->error("===> $task failed, aborting...");
                    exit(0);
                }
            }
        }
    }

    public function execute()
    {
        $config = parse_ini_file('.phprelease');
        if ( isset($config['autoload']) ) {
            if ( $a = $config['autoload'] ) {
                $this->logger->info("===> Found autoload script, loading...");
                $loader = require $a;
            }
        }
        elseif ( file_exists('vendor/autoload.php') ) {
            $this->logger->info("===> Found autoload script, loading...");
            $loader = require "vendor/autoload.php";
        }

        $steps = preg_split('#\s*,\s*#',$config['steps'] );
        $this->runSteps($steps, $this->options->dryrun);
    }
}

