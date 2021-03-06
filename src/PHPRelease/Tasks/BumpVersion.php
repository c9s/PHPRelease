<?php
namespace PHPRelease\Tasks;
use PHPRelease\VersionParser;
use PHPRelease\VersionReader;


class BumpVersion extends BaseTask
{
    public function options($opts)
    {
        $opts->add('bump-major','bump major (X) version.');
        $opts->add('bump-minor','bump minor (Y) version.');
        $opts->add('bump-patch','bump patch (Z) version, this is the default.');
        $opts->add('prompt-version','prompt for version');

        $opts->add('s|stability:','set stability');
        foreach( $this->getStabilityKeys() as $s ) {
            $opts->add($s, "set stability to $s.");
        }
    }

    public function getStabilityKeys()
    {
        return array('dev','rc','rc1','rc2','rc3','rc4','rc5','beta','alpha','stable');
    }

    public function replaceVersionFromSourceFile($file, $newVersionString)
    {
        $content = file_get_contents($file);
        $content = preg_replace( VersionReader::classVersionPattern, "const VERSION = \"$newVersionString\";" , $content);
        $content = preg_replace( VersionReader::phpdocVersionPattern, "@VERSION $newVersionString", $content);
        return file_put_contents($file, $content);
    }

    public function execute()
    {
        $versionString = $this->getCurrentVersion();
        $versionParser = new VersionParser;
        $versionInfo = $versionParser->parseVersionString($versionString);

        if ( $this->options->{"bump-major"} ) {
            $this->bumpMajorVersion($versionInfo);
        } elseif ( $this->options->{"bump-minor"} ) {
            $this->bumpMinorVersion($versionInfo);
        } elseif ( $this->options->{"bump-patch"} ) {
            $this->bumpPatchVersion($versionInfo);
        } else {
            // this is the default behavior
            $this->bumpPatchVersion($versionInfo);
        }


        if ( $s = $this->options->stability ) {
            if ( $s === "stable" ) {
                unset( $versionInfo['stability'] );
            } else {
                $versionInfo['stability'] = $s;
            }
        } else {
            foreach( $this->getStabilityKeys() as $s ) {
                if ( $this->options->{$s} ) {
                    $versionInfo['stability'] = $s;
                    break;
                }
                if ( $s === "stable" ) {
                    unset( $versionInfo['stability'] );
                    break;
                }
            }
        }


        $newVersionString = $this->createVersionString($versionInfo);

        $this->logger->info("Current Version: $versionString");

        if ( $this->options->{"prompt-version"} ) {
            if ( $input = $this->ask("New Version [$newVersionString]:") ) {
                $newVersionString = $input;
            }
        }

        $this->logger->info("===> Version bump from $versionString to $newVersionString");


        $versionFromFiles = $this->getVersionFromFiles();
        foreach( $versionFromFiles as $file ) {
            if ( false === $this->replaceVersionFromSourceFile($file, $newVersionString) ) {
                $this->logger->error("Version update failed: $file");
            }
        }
        $this->writeVersionToPackageINI($newVersionString);
        $this->writeVersionToComposerJson($newVersionString);
    }

    public function getVersionFromFiles()
    {
        if ($file = $this->config('BumpVersion.VersionFrom')) {
            return preg_split('#\s*,\s*#', $file);
        }
        if ($file = $this->config('VersionFrom')) {
            return preg_split('#\s*,\s*#', $file);
        } 
        return array();
    }

    public function getCurrentVersion()
    {
        // XXX: Refactor to FindVersion task.
        $reader = new VersionReader;
        $versionFromFiles = $this->getVersionFromFiles();
        if (! empty($versionFromFiles)) {
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

    public function writeVersionToPackageINI($newVersion)
    {
        if ( file_exists("package.ini") ) {
            $this->logger->debug("Writing version info from package.ini");
            $content = file_get_contents("package.ini");
            if ( preg_replace('#^version\s+=\s+.*?$#ims', "version = $newVersion", $content) ) {
                return file_put_contents("package.ini", $content);
            }
        }
    }

    public function writeVersionToComposerJson($newVersion)
    {
        if ( file_exists("composer.json") ) {
            $this->logger->debug("Writing version info from composer.json");
            $composer = json_decode(file_get_contents("composer.json"),true);
            $composer['version'] = $newVersion;
            return file_put_contents("composer.json", json_encode($composer,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        }
    }

    public function bumpMinorVersion(& $versionInfo)
    {
        $versionInfo['minor'] = (@$versionInfo['minor'] ?: 0) + 1;
        $versionInfo['patch'] = 0;
    }

    public function bumpMajorVersion(& $versionInfo)
    {
        $versionInfo['major'] = (@$versionInfo['major'] ?: 0) + 1;
        $versionInfo['minor'] = 0;
        $versionInfo['patch'] = 0;
    }

    public function bumpPatchVersion(& $versionInfo)
    {
        $versionInfo['patch'] = (@$versionInfo['patch'] ?: 0) + 1;
    }

    public function createVersionString($info)
    {
        $str = sprintf('%d.%d.%d', $info['major'], $info['minor'] , $info['patch'] );
        if ( isset($info['stability']) && $info['stability'] != 'stable' ) {
            $str .= '-' . $info['stability'];
        }
        return $str;
    }
}


