<?php
declare(strict_types=1);

namespace BugReport\Stats;

use Github\Api\ApiInterface;
use Github\Client;
use Github\ResultPagerInterface;

class OpenIssues
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
            if (!$this->isOpen($issue)) {
                continue;
            }

            $createdAt = new \DateTimeImmutable($issue['created_at'], $timezone);

            $age = $this->openForDays($createdAt, $since);

            if ($this->isPullRequest($issue)) {
                $pullRequestsOpenForDays += $age;
                $this->openPullRequests++;
                continue;
            }

            $issuesOpenForDays += $age;

            $this->findOldestOpenIssue($age);
            $this->findNewestOpenIssue($age);

            $this->openIssues++;
        }

        if ($this->openIssues > 0) {
            $this->openIssuesAverageAge = (int) ceil($issuesOpenForDays / $this->openIssues);
        }

        if ($this->openPullRequests > 0) {
            $this->openPullRequestsAverageAge = (int) ceil($pullRequestsOpenForDays / $this->openPullRequests);
        }
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
        return $this->openPullRequestsAverageAge;
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
