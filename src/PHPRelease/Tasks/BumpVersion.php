<?php
namespace PHPRelease\Tasks;

class BumpVersion extends BaseTask
{

    public function options($opts)
    {

    }

    public function run()
    {
        $versionString = null;
        $versionFrom = array();
        if ( isset($this->config['VersionFrom']) && $this->config['VersionFrom'] ) {
            $versionFrom = preg_split('#\s*,\s*#', $this->config['VersionFrom']);
            foreach( $versionFrom as $file ) {
                // get version from PHP files
            }
        } else {
            // TODO: read version from tag ?
            $versionString = $this->readVersionFromComposerJson() ?: $this->readVersionFromPackageINI();
        }
        var_dump( $versionString );
    }

    public function readVersionFromPackageINI()
    {
        if ( file_exists("package.ini") ) {
            $config = parse_ini_file("package.ini",true);
            if ( isset($config['package']['version']) ) {
                return $config['package']['version'];
            }
        }
    }

    public function writeVersionToPackageINI($newVersion)
    {
        if ( file_exists("package.ini") ) {
            $content = file_get_contents("package.ini");
            if ( preg_replace('#^version\s+=\s+.*?$#ims', "version = $newVersion", $content) ) {
                return file_put_contents("package.ini", $content);
            }
        }
    }

    public function readVersionFromComposerJson()
    {
        if ( file_exists("composer.json") ) {
            $composer = json_decode(file_get_contents("composer.json"),true);
            if ( isset($composer['version']) ) {
                return $composer['version'];
            }
        }
    }

    public function writeVersionToComposerJson($newVersion)
    {
        if ( file_exists("composer.json") ) {
            $composer = json_decode(file_get_contents("composer.json"),true);
            $composer['version'] = $newVersion;
            return file_put_contents("composer.json", json_encode($composer,JSON_PRETTY_PRINT));
        }
    }

    public function bumpVersion($version)
    {
        $majorVersion;
        $minorVersion;
        $patchVersion;
    }
}


