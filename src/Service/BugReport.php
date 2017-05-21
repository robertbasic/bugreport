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

    /**
     * @var array
     */
    private $reportLines;

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

        $this->addReportLine('Getting bugreport for ' . $dependencies->total() . ' installed dependencies');

        foreach ($dependencies->all() as $dependency) {
            $this->handleProjectDependency($dependency);
        }
    }

    public function handleProjectDependency(string $dependency)
    {
        $dependency = Dependency::fromUserRepo($dependency);

        $this->addReportLine('Getting bugreport for ' . $dependency->url());

        $issues = $this->issues->fetch($dependency);

        $stats = new DependencyStats($issues);

        $this->addReportLine("Open issues: " . $stats->openIssues());
        $this->addReportLine("Open pull requests: " . $stats->pullRequests());
        $this->addReportLine("Oldest open issue: " . $stats->oldestOpenIssue() . " days");
        $this->addReportLine("Newest open issue: " . $stats->newestOpenIssue() . " days");
        $this->addReportLine("Average age of open issues: " . $stats->openIssuesAverageAge() . " days");
        $this->addReportLine("Average age of open pull requests: " . $stats->pullRequestsAverageAge() . " days");
    }

    public function getReportLines() : array
    {
        $lines = $this->reportLines;

        $this->reportLines = [];

        return $lines;
    }

    private function addReportLine(string $line)
    {
        $this->reportLines[] = $line;
    }
}
