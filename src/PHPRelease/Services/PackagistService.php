<?php
namespace PHPRelease\Services;
use CurlKit\CurlAgent;

class PackagistService
{
    protected $username;

    protected $apiToken;

    public function __construct($username, $apiToken)
    {
        $this->username = $username;
        $this->apiToken = $apiToken;
    }


    /**
     * POST to https://packagist.org/api/update-package?username=c9s&apiToken=API_TOKEN
     *
     * The command:
     *
     *    curl -XPOST -H'content-type:application/json' \
     *         'https://packagist.org/api/update-package?username=c9s&apiToken=API_TOKEN' \
     *         -d'{"repository":{"url":"https://github.com/c9s/PHPRelease"}}'
     */
    public function updatePackage($packageUrl)
    {
        $agent = new CurlAgent;
        $response = $agent->post("https://packagist.org/api/update-package?username={$this->username}&apiToken={$this->apiToken}", json_encode([
          'repository' => ['url' => $packageUrl],
        ]), [ 'Content-Type: application/json' ]);
        if ($response) {
            $result = $response->decodeBody();
            return $result;
        }
        return false;
    }
}




