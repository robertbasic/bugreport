<?php
declare(strict_types=1);

namespace BugReport;

use Github\Api\ApiInterface;
use Github\Client;
use Github\ResultPagerInterface;

class Stats
{
    const OPEN_STATE = 'open';

    /**
     * @var int
     */
    private $openIssues = 0;

    /**
     * @var int
     */
    private $closedIssues = 0;

    /**
     * @var int
     */
    private $openPullRequests = 0;

    public function __construct(array $issues)
    {
        foreach ($issues as $issue) {
            if ($this->isPullRequest($issue) && $this->isOpen($issue)) {
                $this->openPullRequests++;
            } else if ($this->isOpen($issue)) {
                $this->openIssues++;
            } else {
                $this->closedIssues++;
            }
        }
    }

    public function openIssues() : int
    {
        return $this->openIssues;
    }

    public function closedIssues() : int
    {
        return $this->closedIssues;
    }

    public function pullRequests() : int
    {
        return $this->openPullRequests;
    }

    private function isOpen($issue) : bool
    {
        return isset($issue['state']) && $issue['state'] === self::OPEN_STATE;
    }

    private function isPullRequest($issue) : bool
    {
        return isset($issue['pull_request']);
    }
}
