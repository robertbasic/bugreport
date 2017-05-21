<?php
declare(strict_types=1);

namespace BugReport\Service;

use BugReport\Dependency;
use BugReport\GitHub\Issues;
use BugReport\InstalledDependencies;
use BugReport\Service\Config;
use BugReport\Stats\Dependency as DependencyStats;
use Github\Client;
use Github\ResultPager;
use Github\ResultPagerInterface;

class BugReport
{
    const VERSION = '0.0.1-dev';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ResultPagerInterface
     */
    private $pager;

    /**
     * @var ApiInterface
     */
    private $issueApi;

    /**
     * @var Issues
     */
    private $issues;

    public function __construct(Client $client, ResultPagerInterface $pager, Config $config)
    {
        $this->client = $client;

        if ($config->hasConfig()) {
            $this->client->authenticate($config->githubPersonalAccessToken(), null, Client::AUTH_HTTP_TOKEN);
        }

        $this->pager = $pager;

        $this->issueApi = $this->client->issue();

        $this->issues = new Issues($this->pager, $this->issueApi);
    }

    public function handleProjectDependency(Dependency $dependency) : DependencyStats
    {
        $issues = $this->issues->fetch($dependency);

        return new DependencyStats($issues);
    }
}
