<?php
declare(strict_types=1);

namespace BugReport\Service;

use BugReport\Dependency;
use BugReport\Service\GitHub\Issues;
use BugReport\Service\Config;
use BugReport\Stats\OpenIssues;
use BugReport\Formatter\Formatter;

class BugReport
{
    const VERSION = '0.1.0';

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

    public function __construct(Issues $issues, Config $config)
    {
        $this->issues = $issues;

        $this->config = $config;
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
        $report = $formatter->format($this->report);

        $filename = $this->getFilename($formatter);

        file_put_contents($filename, $report);

        $this->clearReport();

        return $filename;
    }

    private function getFilename(Formatter $formatter) : string
    {
        $filename = $this->config->bugreportFilename();
        $pathinfo = pathinfo($filename);

        return $pathinfo['filename'] . '.' . $formatter->extension();
    }

    private function clearReport()
    {
        $this->report = [];
    }
}
