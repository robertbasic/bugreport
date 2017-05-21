<?php
declare(strict_types=1);

namespace BugReport\Service;

use BugReport\Dependency;
use BugReport\GitHub\Issues;
use BugReport\InstalledDependencies;
use BugReport\Stats\Dependency as DependencyStats;
use Github\Client;
use Github\ResultPager;
use Github\ResultPagerInterface;

class BugReport
{
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

    public function __construct(Client $client, ResultPagerInterface $pager)
    {
        $this->client = $client;

        $this->pager = $pager;

        $this->issueApi = $this->client->issue();

        $this->issues = new Issues($this->pager, $this->issueApi);
    }

    public function handleProjectDependencies(string $lockfile)
    {
        $dependencies = InstalledDependencies::fromComposerLockFile($lockfile);

        foreach ($dependencies->all() as $dependency) {
            $this->handleProjectDependency($dependency);
        }
    }

    public function handleProjectDependency(string $dependency)
    {
        $dependency = Dependency::fromUserRepo($dependency);

        $issues = $this->issues->fetch($dependency);
        $stats = new DependencyStats($issues);
    }
}
