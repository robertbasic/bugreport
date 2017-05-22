<?php
declare(strict_types=1);

namespace BugReport\Service;

use BugReport\Dependency;
use BugReport\GitHub\Issues;
use BugReport\InstalledDependencies;
use BugReport\Service\Config;
use BugReport\Stats\OpenIssues;
use BugReport\Formatter\Formatter;
use Github\Client;
use Github\ResultPager;
use Github\ResultPagerInterface;

class BugReport
{
    const VERSION = '0.0.2-dev';

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
    private $report;

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

    public function isConfigured() : bool
    {
        return $this->config->hasConfig();
    }

    public function handleProjectDependency(Dependency $dependency)
    {
        $issues = $this->issues->fetch($dependency);

        $openIssues = new OpenIssues($issues);

        $line = [
            'dependency' => $dependency,
            'open_issues' => $openIssues,
        ];

        $this->report[] = $line;
    }

    public function saveReport(Formatter $formatter) : string
    {
        $filename = $this->config->bugreportFilename();

        $report = $formatter->format($this->report);

        file_put_contents($filename, $report);

        $this->clearReport();

        return $filename;
    }

    private function clearReport()
    {
        $this->report = [];
    }
}
