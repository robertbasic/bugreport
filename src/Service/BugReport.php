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
     * @var Issues
     */
    private $issues;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $reportLines;

    public function __construct(Client $client, ResultPagerInterface $pager, Config $config)
    {
        $this->client = $client;

        if ($config->hasConfig()) {
            $this->client->authenticate($config->githubPersonalAccessToken(), null, Client::AUTH_HTTP_TOKEN);
        }

        $this->config = $config;

        $issueApi = $this->client->issue();

        $this->issues = new Issues($pager, $issueApi);
    }

    public function handleProjectDependency(Dependency $dependency)
    {
        $this->addReportHeader($dependency);

        $issues = $this->issues->fetch($dependency);

        $stats = new DependencyStats($issues);

        $this->addReportLine("Open issues: " . $stats->openIssues());
        $this->addReportLine("Open pull requests: " . $stats->pullRequests());
        $this->addReportLine("Oldest open issue: " . $stats->oldestOpenIssue() . " days");
        $this->addReportLine("Newest open issue: " . $stats->newestOpenIssue() . " days");
        $this->addReportLine("Average age of open issues: " . $stats->openIssuesAverageAge() . " days");
        $this->addReportLine("Average age of open pull requests: " . $stats->pullRequestsAverageAge() . " days");
    }

    public function saveReport() : string
    {
        $filename = $this->config->bugreportFilename();

        // Add empty line at the end
        $this->addReportLine("");

        file_put_contents($filename, implode("\n", $this->reportLines));

        $this->clearReportLines();

        return $filename;
    }

    private function clearReportLines()
    {
        $this->reportLines = [];
    }

    private function addReportLine(string $line)
    {
        $this->reportLines[] = $line;
    }

    private function addReportHeader(Dependency $dependency)
    {
        $this->addReportLine(str_repeat('#', 20));
        $this->addReportLine('bugreport for ' . $dependency->url());
        $this->addReportLine(str_repeat('#', 20));
    }
}
