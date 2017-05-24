<?php
declare(strict_types=1);

namespace BugReport\Service\GitHub;

use BugReport\Dependency;
use Github\Api\ApiInterface;
use Github\ResultPagerInterface;

class Issues
{
    const API_METHOD = 'all';

    const STATE = 'open';

    /**
     * @var ResultPagerInterface
     */
    private $pager;

    /**
     * @var ApiInterface
     */
    private $issueApi;

    public function __construct(ResultPagerInterface $pager, ApiInterface $issueApi)
    {
        $this->pager = $pager;
        $this->issueApi = $issueApi;
    }

    public function fetch(Dependency $dependency) : array
    {
        $params = [
            $dependency->user(),
            $dependency->repo(),
            ['state' => self::STATE],
        ];

        $issues = $this->pager->fetchAll($this->issueApi, self::API_METHOD, $params);

        return $issues;
    }
}
