<?php

class BumpVersionTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $b = new PHPRelease\Tasks\BumpVersion(null,null,null);
        $p = new PHPRelease\VersionParser;
        $info = $p->parseVersionString('1.2.3');
        ok($info);
        is(1,$info['major']);
        is(2,$info['minor']);
        is(3,$info['patch']);
        ok( ! $info['stability'] );

        $b->bumpMajorVersion($info);
        is(2,$info['major']);

        $b->bumpMinorVersion($info);
        is(3,$info['minor']);

        $b->bumpPatchVersion($info);
        is(4,$info['patch']);

        // is('0.0.2',$b->bumpPatchVersion('0.0.1'));
    }

    public function testPHPDocVersionParsing()
    {
        $b = new PHPRelease\Tasks\BumpVersion(null,null,null,null);
        is('0.0.1',$b->parseVersionFromSourceFile("tests/data/test.php"));
    }

    public function testClassVersionConstParsing()
    {
        $b = new PHPRelease\Tasks\BumpVersion(null,null,null,null);
        is('0.0.1',$b->parseVersionFromSourceFile("tests/data/test2.php"));
    }
}

