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
    private $openPullRequests = 0;

    /**
     * @var int
     */
    private $ageOfOldestOpenIssue = 0;

    /**
     * @var int
     */
    private $ageOfNewestOpenIssue = 0;

    /**
     * @var int
     */
    private $openIssuesAverageAge = 0;

    /**
     * @var int
     */
    private $openPullRequestsAverageAge = 0;

    public function __construct(array $issues, \DateTimeImmutable $since = null)
    {
        $timezone = new \DateTimeZone('UTC');

        if ($since === null) {
            $since = new \DateTimeImmutable('now', $timezone);
        }

        $pullRequestsOpenForDays = 0;
        $issuesOpenForDays = 0;

        foreach ($issues as $issue) {
            $createdAt = new \DateTimeImmutable($issue['created_at'], $timezone);

            if ($this->isPullRequest($issue) && $this->isOpen($issue)) {
                $pullRequestsOpenForDays += $this->openForDays($createdAt, $since);

                $this->openPullRequests++;
            } elseif ($this->isOpen($issue)) {
                $ageOfIssue = $this->openForDays($createdAt, $since);
                $issuesOpenForDays += $ageOfIssue;

                $this->findOldestOpenIssue($ageOfIssue);
                $this->findNewestOpenIssue($ageOfIssue);

                $this->openIssues++;
            }
        }

        $this->openIssuesAverageAge = (int) ceil($issuesOpenForDays / $this->openIssues);
        $this->pullRequestsAverageAge = (int) ceil($pullRequestsOpenForDays / $this->openPullRequests);
    }

    public function openIssues() : int
    {
        return $this->openIssues;
    }

    public function pullRequests() : int
    {
        return $this->openPullRequests;
    }

    public function oldestOpenIssue() : int
    {
        return $this->ageOfOldestOpenIssue;
    }

    public function newestOpenIssue() : int
    {
        return $this->ageOfNewestOpenIssue;
    }

    public function openIssuesAverageAge() : int
    {
        return $this->openIssuesAverageAge;
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

    private function openForDays(\DateTimeImmutable $createdAt, \DateTimeImmutable $since) : int
    {
        return $since->diff($createdAt)->days;
    }

    private function findOldestOpenIssue(int $ageOfIssue)
    {
        if ($this->ageOfOldestOpenIssue < $ageOfIssue) {
            $this->ageOfOldestOpenIssue = $ageOfIssue;
        }
    }

    private function findNewestOpenIssue(int $ageOfIssue)
    {
        if ($this->ageOfNewestOpenIssue === 0 || $this->ageOfNewestOpenIssue > $ageOfIssue) {
            $this->ageOfNewestOpenIssue = $ageOfIssue;
        }
    }
}
