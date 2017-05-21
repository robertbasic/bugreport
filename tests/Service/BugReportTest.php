<?php
declare(strict_types=1);

namespace BugReportTest\Service;

use BugReport\Dependency;
use BugReport\Service\BugReport;
use BugReport\Service\Config;
use BugReport\Stats\Dependency as DependencyStats;
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

        $this->config = Mockery::mock(Config::class);
        $this->config->shouldReceive()
            ->hasConfig()
            ->andReturn(false)
            ->byDefault();

        $this->client->shouldReceive()
            ->issue()
            ->andReturn($this->issueApi);

        $this->service = new BugReport($this->client, $this->pager, $this->config);
    }

    /**
     * @test
     */
    public function it_executes_for_a_provided_dependency()
    {
        $dependency = Dependency::fromUserRepo('mockery/mockery');

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

        $result = $this->service->handleProjectDependency($dependency);

        $this->assertInstanceOf(DependencyStats::class, $result);
    }

    /**
     * @test
     */
    public function it_authenticates_if_configured()
    {
        $dependency = 'mockery/mockery';

        $this->config->shouldReceive()
            ->hasConfig()
            ->once()
            ->andReturn(true);

        $this->config->shouldReceive()
            ->githubPersonalAccessToken()
            ->once()
            ->andReturn('github-pat');

        $this->client->shouldReceive()
            ->authenticate('github-pat', null, Client::AUTH_HTTP_TOKEN)
            ->once();

        $this->pager->shouldReceive()
            ->fetchAll($this->issueApi, 'all', Mockery::any())
            ->andReturn([]);

        $service = new BugReport($this->client, $this->pager, $this->config);
    }
}
