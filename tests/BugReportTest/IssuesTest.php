<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\Issues;
use BugReport\Project;
use Github\Api\ApiInterface;
use Github\ResultPagerInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Mockery;

class IssuesTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var Issues
     */
    private $issues;

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
    private $api;

    /**
     * @var array
     */
    private $params;

    public function setup()
    {
        $this->project = Project::fromUserRepo('mockery/mockery');
        $this->pager = Mockery::mock(ResultPagerInterface::class);
        $this->api = Mockery::mock(ApiInterface::class);

        $this->params = [
            $this->project->user(),
            $this->project->repo(),
            ['state' => 'all']
        ];

        $this->apiResponse = include_once __DIR__ . '/fixtures/issues_mockery_all.php';

        $this->issues = new Issues($this->project, $this->pager, $this->api);
    }

    /**
     * @test
     */
    public function it_fetches_all_issues()
    {
        $this->pager->shouldReceive()
            ->fetchAll($this->api, 'all', $this->params)
            ->andReturn($this->apiResponse);

        $this->issues->__invoke();

        $this->assertSame(41, $this->issues->open());
        $this->assertSame(0, $this->issues->closed());
        $this->assertSame(9, $this->issues->pullRequests());
    }
}
