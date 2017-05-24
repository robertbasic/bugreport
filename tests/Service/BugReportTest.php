<?php
declare(strict_types=1);

namespace BugReportTest\Service;

use BugReport\Dependency;
use BugReport\Formatter\Html;
use BugReport\Formatter\Text;
use BugReport\Service\BugReport;
use BugReport\Service\Config;
use BugReport\Service\GitHub\Issues;
use BugReport\Stats\Dependency as DependencyStats;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class BugReportTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var Issues|MockInterface
     */
    private $issues;

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
        $this->issues = Mockery::mock(Issues::class);

        $this->config = Mockery::mock(Config::class);
        $this->config->shouldReceive()
            ->hasConfig()
            ->andReturn(false)
            ->byDefault();

        $this->service = new BugReport($this->issues, $this->config);
    }

    /**
     * @test
     */
    public function it_handles_a_project_dependency()
    {
        $dependency = Dependency::fromUserRepo('mockery/mockery');

        $issues = include __DIR__ . '/../fixtures/issues_mockery_all.php';

        $this->issues->shouldReceive()
            ->fetch($dependency)
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

        $issues = [];

        $this->issues->shouldReceive()
            ->fetch($dependency)
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

        $issues = [];

        $this->issues->shouldReceive()
            ->fetch($dependency)
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
    public function it_is_configured()
    {
        $config = new Config(__DIR__ . '/../fixtures/bugreport_config.json');
        $service = new BugReport($this->issues, $config);

        $this->assertTrue($service->isConfigured());
    }
}
