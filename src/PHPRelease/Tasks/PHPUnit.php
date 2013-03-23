<?php
namespace PHPRelease\Tasks;

class PHPUnit extends BaseTask
{
    public function execute()
    {
        $this->logger->info("Running phpunit...");
        passthru("phpunit", $retval);
        return $retval == 0;
    }
}



