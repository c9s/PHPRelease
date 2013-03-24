<?php
namespace PHPRelease;

class VersionParser
{

    public function parseVersionString($version)
    {
        if ( preg_match('#^(\d+)\.(\d+)(?:\.(\d+))?(?:-(dev|alpha|beta|rc\d*))?$#x',$version, $regs ) ) {
            return array(
                'major' => $regs[1],
                'minor' => (@$regs[2] ?: 0),
                'patch' => (@$regs[3] ?: 0),
                'stability' => (@$regs[4] ?: null),
            );
        }
        return array();
    }

}

