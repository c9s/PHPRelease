<?php
namespace PHPRelease\Tasks;
use PHPRelease\VersionParser;
use PHPRelease\VersionReader;


class BumpVersion extends BaseTask
{
    public $classVersionPattern = '#const\s+version\s+=\s+["\'](.*?)["\'];#i';
    public $phpdocVersionPattern = '#@version\s+(\S+)#i';

    public function options($opts)
    {
        $opts->add('bump-major','bump major (X) version.');
        $opts->add('bump-minor','bump minor (Y) version.');
        $opts->add('bump-patch','bump patch (Z) version, this is the default.');
    }

    public function getVersionFromFiles()
    {
        if ( isset($this->config['VersionFrom']) && $this->config['VersionFrom'] ) {
            return preg_split('#\s*,\s*#', $this->config['VersionFrom']);
        }
        return array();
    }



    public function parseVersionFromSourceFile($file)
    {
        $content = file_get_contents($file);
        // find class const
        if ( preg_match( $this->classVersionPattern, $content, $regs) ) {
            return $regs[1];
        } elseif ( preg_match( $this->phpdocVersionPattern, $content, $regs) ) {
            return $regs[1];
        }
    }

    public function replaceVersionFromSourceFile($file, $newVersionString)
    {
        $content = file_get_contents($file);
        $content = preg_replace( VersionReader::classVersionPattern, 'const VERSION = "\1";' , $content);
        $content = preg_replace( VersionReader::phpdocVersionPattern, '@VERSION \1', $content);
        return file_put_contents($file, $content);
    }

    public function run()
    {
        $reader = new VersionReader;
        $versionString = null;
        $versionFromFiles = $this->getVersionFromFiles();

        if ( ! empty($versionFromFiles) ) {
            $versionString = $reader->readFromSourceFiles($versionFromFiles);
        }
        if ( ! $versionString ) {
            $this->logger->debug("Reading version info from composer.json");
            $versionString = $reader->readFromComposerJson();
        }
        if ( ! $versionString ) {
            $this->logger->debug("Reading version info from package.ini");
            $versionString = $reader->readFromPackageINI();
        }

        if ( ! $versionString ) {
            $this->logger->error("Version string not found, aborting...");
            return false;
        }

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
        $this->logger->info("===> Version bump from $versionString to $newVersionString");


        foreach( $versionFromFiles as $file ) {
            if ( false === $this->replaceVersionFromSourceFile($file, $newVersionString) ) {
                $this->logger->error("Version update failed: $file");
            }
        }
        $this->writeVersionToPackageINI($newVersionString);
        $this->writeVersionToComposerJson($newVersionString);

        $this->config['CurrentVersion'] = $newVersionString;
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
        if ( $info['stability'] && $info['stability'] != 'stable' ) {
            $str .= '-' . $info['stability'];
        }
        return $str;
    }
}


