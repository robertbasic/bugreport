<?php
declare(strict_types=1);

namespace BugReport\Service;

use BugReport\Service\BugReport;
use BugReport\Service\Config;
use BugReport\Service\GitHub\Issues;
use BugReport\Service\GitHub\IssuesFactory;
use Github\Api\ApiInterface;
use Github\ResultPagerInterface;

class BugReportFactory
{
    public function __invoke(string $configFile) : BugReport
    {
        $config = new Config($configFile);
        $issues = (new IssuesFactory())($config);

        return new BugReport($issues, $config);
    }
}
