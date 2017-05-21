<?php
declare(strict_types=1);

namespace BugReportTest\Service;

use BugReport\Service\BugReport;
use BugReport\Service\Config;
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
