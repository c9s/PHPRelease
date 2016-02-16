<?php
use PHPRelease\Services\PackagistService;

class PackagistServiceTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $username = getenv('PACKAGIST_USERNAME');
        $apiToken = getenv('PACKAGIST_APITOKEN');

        if (!$apiToken || !$usernmae) {
            return $this->markTestSkipped('PackagistServiceTest requires PACKAGIST_USERNAME and PACKAGIST_APITOKEN to be setup.');
        }
        $service = new PackagistService($username, $apiToken);
        $result = $service->updatePackage('https://github.com/c9s/PHPRelease');
        var_dump($result);
    }

}
