<?php
declare(strict_types=1);

namespace BugReportTest\Service;

use BugReport\Dependency;
use BugReport\Formatter\Html;
use BugReport\Formatter\Text;
use BugReport\Service\BugReport;
use BugReport\Service\Config;
use BugReport\Stats\Dependency as DependencyStats;
use Github\Api\ApiInterface;
use Github\Client;
use Github\ResultPagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class BugReportTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var Client|MockInterface
     */
    private $client;

    /**
     * @var ResultPagerInterface|MockInterface
     */
    private $pager;

    /**
     * @var ApiInterface|MockInterface
     */
    private $issueApi;

    /**
     * @var Config|MockInterface
     */
    private $config;

    /**
     * @var BugReport
     */
    private $service;

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
    public function it_handles_a_project_dependency()
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

        $this->service->handleProjectDependency($dependency);
    }

    /**
     * @test
     */
    public function it_can_save_the_report_to_a_text_file()
    {
        $dependency = Dependency::fromUserRepo('mockery/mockery');

        $params = [
            'mockery',
            'mockery',
            ['state' => 'open'],
        ];

        $issues = [];

        $this->pager->shouldReceive()
            ->fetchAll($this->issueApi, 'all', $params)
            ->once()
            ->andReturn($issues);

        $this->config->shouldReceive()
            ->bugreportFilename()
            ->once()
            ->andReturn('test_bugreport.txt');

        $formatter = new Text();

        $this->service->handleProjectDependency($dependency);

        $filename = $this->service->saveReport($formatter);

        $this->assertFileExists($filename);

        unlink($filename);
    }

    /**
     * @test
     */
    public function it_can_save_the_report_to_a_html_file()
    {
        $dependency = Dependency::fromUserRepo('mockery/mockery');

        $params = [
            'mockery',
            'mockery',
            ['state' => 'open'],
        ];

        $issues = [];

        $this->pager->shouldReceive()
            ->fetchAll($this->issueApi, 'all', $params)
            ->once()
            ->andReturn($issues);

        $this->config->shouldReceive()
            ->bugreportFilename()
            ->once()
            ->andReturn('test_bugreport.html');

        $formatter = new Html();

        $this->service->handleProjectDependency($dependency);

        $filename = $this->service->saveReport($formatter);

        $this->assertFileExists($filename);

        unlink($filename);
    }

    /**
     * @test
     */
    public function it_authenticates_if_configured()
    {
        $dependency = 'mockery/mockery';

        $this->config->shouldReceive()
            ->hasConfig()
            ->twice()
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

        $this->assertTrue($service->isConfigured());
    }
}
