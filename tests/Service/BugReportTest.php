<?php
declare(strict_types=1);

namespace BugReportTest\Service;

use BugReport\Service\BugReport;
use Github\Api\ApiInterface;
use Github\Client;
use Github\ResultPagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class BugReportTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ResultPagerInterface
     */
    private $pager;

    /**
     * @var ApiInterface
     */
    private $issueApi;

    public function setup()
    {
        $this->client = Mockery::mock(Client::class);
        $this->pager = Mockery::mock(ResultPagerInterface::class);
        $this->issueApi = Mockery::mock(ApiInterface::class);

        $this->client->shouldReceive()
            ->issue()
            ->andReturn($this->issueApi);

        $this->service = new BugReport($this->client, $this->pager);
    }

    /**
     * @test
     */
    public function it_executes_for_a_provided_dependency()
    {
        $dependency = 'mockery/mockery';

        $params = [
            'mockery',
            'mockery',
            ['state' => 'open'],
        ];

        $issues = include __DIR__ . '/../fixtures/issues_mockery_all.php';

        $this->pager->shouldReceive()
            ->fetchAll($this->issueApi, 'all', $params)
            ->once()
            ->andReturn($issues);

        $this->service->handleProjectDependency($dependency);

        $reportLines = $this->service->getReportLines();

        // Assert the first call emptied the report lines
        $this->assertEmpty($this->service->getReportLines());
    }

    /**
     * @test
     */
    public function it_executes_for_an_existing_composer_lock_file()
    {
        $dependency = getcwd() . '/tests/fixtures/composer.lock';

        $this->pager->shouldReceive()
            ->fetchAll($this->issueApi, 'all', Mockery::type('array'))
            ->times(50)
            ->andReturn([]);

        $this->service->handleProjectDependencies($dependency);
    }
}
