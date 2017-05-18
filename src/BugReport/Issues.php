<?php
declare(strict_types=1);

namespace BugReport;

use Github\Api\ApiInterface;
use Github\Client;
use Github\ResultPagerInterface;

class Issues
{
    const API_METHOD = 'all';

    const STATE = 'all';

    const OPEN_STATE = 'open';

    /**
     * @var Project
     */
    private $project;

    /**
     * @var ResultPagerInterface
     */
    private $pager;

    /**
     * @var ApiInterface
     */
    private $issueApi;

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

    public function __construct(Project $project, ResultPagerInterface $pager, ApiInterface $issueApi)
    {
        $this->project = $project;
        $this->pager = $pager;
        $this->issueApi = $issueApi;
    }

    public function __invoke()
    {
        $params = [
            $this->project->user(),
            $this->project->repo(),
            ['state' => self::STATE],
        ];

        $issues = $this->pager->fetchAll($this->issueApi, self::API_METHOD, $params);

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

    public function open() : int
    {
        return $this->openIssues;
    }

    public function closed() : int
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
