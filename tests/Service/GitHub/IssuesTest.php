<?php
declare(strict_types=1);

namespace BugReportTest\Service\GitHub;

use BugReport\Dependency;
use BugReport\Service\GitHub\Issues;
use Github\Api\ApiInterface;
use Github\ResultPagerInterface;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class IssuesTest extends TestCase
{

    /**
     * @var Dependency
     */
    private $dependency;

    /**
     * @var ResultPagerInterface|MockInterface
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

    /**
     * @var array
     */
    private $apiResponse;

    /**
     * @var Issues
     */
    private $issues;

    public function setup()
    {
        $this->dependency = Dependency::fromUserRepo('mockery/mockery');
        $this->pager = Mockery::mock(ResultPagerInterface::class);
        $this->api = Mockery::mock(ApiInterface::class);

        $this->params = [
            $this->dependency->user(),
            $this->dependency->repo(),
            ['state' => 'open']
        ];

        $this->apiResponse = include __DIR__ . '/../../fixtures/issues_mockery_all.php';

        $this->issues = new Issues($this->pager, $this->api);
    }

    /**
     * @test
     */
    public function it_fetches_all_issues()
    {
        $this->pager->shouldReceive()
            ->fetchAll($this->api, 'all', $this->params)
            ->andReturn($this->apiResponse);

        $result = $this->issues->fetch($this->dependency);

        $this->assertEquals(50, count($result));
    }
}
