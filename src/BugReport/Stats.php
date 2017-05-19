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

    /**
     * @var int
     */
    private $openPullRequestsAverageAge = 0;

    public function __construct(array $issues)
    {
        $pullRequestsOpenForDays = 0;

        $timezone = new \DateTimeZone('UTC');
        $now = new \DateTime('now', $timezone);

        foreach ($issues as $issue) {
            if ($this->isPullRequest($issue) && $this->isOpen($issue)) {
                $pullRequestsOpenForDays += $this->openForDays($issue, $now);

                $this->openPullRequests++;
            } elseif ($this->isOpen($issue)) {
                $this->openIssues++;
            } else {
                $this->closedIssues++;
            }
        }

        $this->pullRequestsAverageAge = (int) ceil($pullRequestsOpenForDays / $this->openPullRequests);
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

    public function pullRequestsAverageAge() : int
    {
        return $this->pullRequestsAverageAge;
    }

    private function isOpen(array $issue) : bool
    {
        return isset($issue['state']) && $issue['state'] === self::OPEN_STATE;
    }

    private function isPullRequest(array $issue) : bool
    {
        return isset($issue['pull_request']);
    }

    private function openForDays(array $issue, \DateTime $now) : int
    {
        $createdAt = new \DateTime($issue['created_at'], $now->getTimeZone());
        return $now->diff($createdAt)->days;
    }
}
