<?php

class VersionParserTest extends PHPUnit_Framework_TestCase
{
    public function testBasicVersion()
    {
        $p = new PHPRelease\VersionParser;
        $info = $p->parseVersionString('1.2.3');
        ok($info);
        is(1,$info['major']);
        is(2,$info['minor']);
        is(3,$info['patch']);
        ok( ! $info['stability'] );
    }

    public function testVersionWithRCStability()
    {
        $p = new PHPRelease\VersionParser;
        $info = $p->parseVersionString('1.2.3-rc');
        ok($info);
        is(1,$info['major']);
        is(2,$info['minor']);
        is(3,$info['patch']);
        is( 'rc', $info['stability'] );
    }

    public function testVersionWithAlphaStability()
    {
        $p = new PHPRelease\VersionParser;
        $info = $p->parseVersionString('1.2.3-alpha');
        ok($info);
        is(1,$info['major']);
        is(2,$info['minor']);
        is(3,$info['patch']);
        is( 'alpha', $info['stability'] );
    }
}

