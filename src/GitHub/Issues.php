<?php
declare(strict_types=1);

namespace BugReport\GitHub;

use BugReport\Project;
use Github\Api\ApiInterface;
use Github\ResultPagerInterface;

class Issues
{
    const API_METHOD = 'all';

    const STATE = 'open';

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

    public function __construct(Project $project, ResultPagerInterface $pager, ApiInterface $issueApi)
    {
        $this->project = $project;
        $this->pager = $pager;
        $this->issueApi = $issueApi;
    }

    public function fetch() : array
    {
        $params = [
            $this->project->user(),
            $this->project->repo(),
            ['state' => self::STATE],
        ];

        $issues = $this->pager->fetchAll($this->issueApi, self::API_METHOD, $params);

        return $issues;
    }
}
