<?php
namespace PHPRelease;
use CLIFramework\Application;
use RuntimeException;
use PHPRelease\VersionReader;

class Console extends Application
{
    const NAME = "PHPRelease";
    const VERSION = "1.0.18";

    public function brief()
    {
        return "PHPRelease - The Fast PHP Release Manager.";
    }

    public $config = array();

    public function options($opts)
    {
        parent::options($opts);
        $opts->add('dryrun','dryrun mode.');
        foreach( $this->getTaskObjects() as $task ) {
            $task->options($opts);
        }
    }

    public function findTaskClass($step)
    {
        if ( class_exists( $step, true ) ) {
            return $step;
        } else {
            // built-in task
            $class = 'PHPRelease\\Tasks\\' . $step;
            if ( class_exists($class, true ) ) {
                return $class;
            }
        }
    }

    public function createTaskObject($class)
    {
        $task = $this->createCommand($class);
        $task->setConfig($this->getConfig());
        $task->setOptions($this->options);
        return $task;
    }

    public function getTaskObjects()
    {
        $tasks = array();
        $steps = $this->getSteps();
        foreach( $steps as $step ) {
            if ( file_exists($step) ) {
                continue;
            }

            $taskClass = $this->findTaskClass($step);
            if ( ! $taskClass ) {
                throw new Exception("Task class for $step not found.");
            }
            $task = $this->createTaskObject($taskClass);
            $tasks[] = $task;
        }
        return $tasks;
    }


    public function getVersionFromFiles()
    {
        if ( isset($this->config['VersionFrom']) && $this->config['VersionFrom'] ) {
            return preg_split('#\s*,\s*#', $this->config['VersionFrom']);
        }
        return array();
    }

    public function getCurrentVersion()
    {
        // XXX: Refactor to FindVersion task.
        $reader = new VersionReader;
        $versionFromFiles = $this->getVersionFromFiles();
        if ( ! empty($versionFromFiles) ) {
            if ( $versionString = $reader->readFromSourceFiles($versionFromFiles) ) {
                $this->logger->debug("Found version from source files.");
                return $versionString;
            }
        }

        if ( $versionString = $reader->readFromComposerJson() ) {
            $this->logger->debug("Found version from composer.json");
            return $versionString;
        }

        if ( $versionString = $reader->readFromPackageINI() ) {
            $this->logger->debug("Found version from package.ini");
            return $versionString;
        }

        $this->logger->error("Version string not found, aborting...");
        return false;
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
                continue;
            }

            $taskClass = $this->findTaskClass($step);
            if ( ! $taskClass ) {
                $this->logger->error("===> Taks $step not found, aborting...");
                exit(0);
            }

            $task = $this->createTaskObject($taskClass);

            $this->logger->info("===> Running $taskClass");
            if ( ! $dryrun ) {
                $retval = $task->execute();
                if ( false === $retval ) {
                    $this->logger->error("===> $taskClass failed, aborting...");
                    exit(0);
                }
            }
        }
    }


    public function getSteps()
    {
        $config = $this->getConfig();
        return preg_split('#\s*,\s*#', $config['Steps'] );
    }

    public function getConfig()
    {
        if ( $this->config ) {
            return $this->config;
        }
        $config = parse_ini_file('phprelease.ini');
        return $this->config = $config;
    }


    public function execute()
    {
        $config = $this->getConfig();
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


        $steps = $this->getSteps();
        $this->runSteps($steps, $this->options->dryrun);
    }
}

