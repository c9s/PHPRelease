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
        $versionString = $this->application->getCurrentVersion();
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

        $newVersionString = $this->createVersionString($versionInfo);

        $this->logger->info("Current Version: $versionString");

        if ( $this->options->{"prompt-version"} ) {
            if ( $input = $this->ask("New Version [$newVersionString]:") ) {
                $newVersionString = $input;
            }
        }

        $this->logger->info("===> Version bump from $versionString to $newVersionString");


        $versionFromFiles = $this->application->getVersionFromFiles();
        foreach( $versionFromFiles as $file ) {
            if ( false === $this->replaceVersionFromSourceFile($file, $newVersionString) ) {
                $this->logger->error("Version update failed: $file");
            }
        }
        $this->writeVersionToPackageINI($newVersionString);
        $this->writeVersionToComposerJson($newVersionString);
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
            return file_put_contents("composer.json", json_encode($composer,JSON_PRETTY_PRINT));
        }
    }

    public function bumpMinorVersion(& $versionInfo)
    {
        $versionInfo['minor'] = (@$versionInfo['minor'] ?: 0) + 1;
    }

    public function bumpMajorVersion(& $versionInfo)
    {
        $versionInfo['major'] = (@$versionInfo['major'] ?: 0) + 1;
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


