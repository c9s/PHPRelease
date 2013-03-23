<?php
namespace PHPRelease;

class VersionReader
{
    const classVersionPattern = '#const\s+version\s+=\s+["\'](.*?)["\'];#i';
    const phpdocVersionPattern = '#@version\s+(\S+)#i';

    public function readFromSourceFile($file)
    {
        $content = file_get_contents($file);
        // find class const
        if ( preg_match( self::classVersionPattern, $content, $regs) ) {
            return $regs[1];
        } elseif ( preg_match( self::phpdocVersionPattern, $content, $regs) ) {
            return $regs[1];
        }
    }

    public function readFromPackageINI()
    {
        if ( file_exists("package.ini") ) {
            $config = parse_ini_file("package.ini",true);
            if ( isset($config['package']['version']) ) {
                return $config['package']['version'];
            }
        }
    }


    public function readFromComposerJson()
    {
        if ( file_exists("composer.json") ) {
            $composer = json_decode(file_get_contents("composer.json"),true);
            if ( isset($composer['version']) ) {
                return $composer['version'];
            }
        }
    }

}


