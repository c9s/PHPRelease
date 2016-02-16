<?php
use PHPRelease\Services\PackagistService;

class PackagistServiceTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $username = getenv('PACKAGIST_USERNAME');
        $apiToken = getenv('PACKAGIST_APITOKEN');

        if (!$apiToken || !$usernmae) {
            return $this->markTestSkipped();
        }
        $service = new PackagistService($username, $apiToken);
        $result = $service->updatePackage('https://github.com/c9s/PHPRelease');
        var_dump($result);
    }

}
