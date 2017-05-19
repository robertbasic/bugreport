<?php
declare(strict_types=1);

namespace BugReportTest\GitHub;

use BugReport\GitHub\Issues;
use BugReport\Project;
use Github\Api\ApiInterface;
use Github\ResultPagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class IssuesTest extends TestCase
{
    public function setup()
    {
        $this->project = Project::fromUserRepo('mockery/mockery');
        $this->pager = Mockery::mock(ResultPagerInterface::class);
        $this->api = Mockery::mock(ApiInterface::class);

        $this->params = [
            $this->project->user(),
            $this->project->repo(),
            ['state' => 'open']
        ];

        $this->apiResponse = include __DIR__ . '/../fixtures/issues_mockery_all.php';

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

        $result = $this->issues->fetch();

        $this->assertEquals(50, count($result));
    }
}