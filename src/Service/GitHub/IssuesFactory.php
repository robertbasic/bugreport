<?php
declare(strict_types=1);

namespace BugReport\Service\GitHub;

use BugReport\Service\Config;
use BugReport\Service\GitHub\ClientFactory;
use BugReport\Service\GitHub\Issues;
use Github\Client;
use Github\ResultPager;

class IssuesFactory
{
    public function __invoke(Config $config) : Issues
    {
        $client = (new ClientFactory())($config);
        $pager = new ResultPager($client);
        $issueApi = $client->issue();

        return new Issues($pager, $issueApi);
    }
}
