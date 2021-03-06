<?php
declare(strict_types=1);

namespace BugReportTest\Command;

use BugReport\Command\BugReport;
use BugReport\Dependency;
use BugReport\Formatter\Html;
use BugReport\Formatter\Text;
use BugReport\Service\BugReport as BugReportService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugReportTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var InputInterface|MockInterface
     */
    private $input;

    /**
     * @var OutputInterface|MockInterface
     */
    private $output;

    /**
     * @var BugReportService|MockInterface
     */
    private $service;

    public function setup()
    {
        $this->input = Mockery::mock(InputInterface::class);
        $this->input->shouldReceive()
            ->getOption('html')
            ->once()
            ->andReturn(false)
            ->byDefault();

        $this->output = Mockery::mock(OutputInterface::class);

        // For the ProgressBar
        $this->output->shouldReceive()
            ->isDecorated()
            ->andReturn(false)
            ->byDefault();
        $this->output->shouldReceive()
            ->getVerbosity()
            ->andReturn(16) // VERBOSITY_QUIET
            ->byDefault();

        $this->service = Mockery::mock(BugReportService::class);
        $this->service->shouldReceive()
            ->isConfigured()
            ->once();
    }

    /**
     * @test
     */
    public function it_executes_for_a_provided_dependency()
    {
        $this->input->shouldReceive()
            ->getArgument('dependency')
            ->once()
            ->andReturn('mockery/mockery');

        $this->output->shouldReceive()
            ->writeln(Mockery::any());

        $this->service->shouldReceive()
            ->handleProjectDependency(Mockery::type(Dependency::class))
            ->once();

        $this->service->shouldReceive()
            ->saveReport(Mockery::type(Text::class))
            ->once();

        $command = new BugReportTestCommand($this->service);
        $command->execute($this->input, $this->output);
    }

    /**
     * @test
     */
    public function it_executes_for_an_existing_composer_lock_file_when_not_provided_as_argument()
    {
        $lockfile = getcwd() . '/tests/fixtures/composer.lock';

        $this->input->shouldReceive()
            ->getArgument('dependency')
            ->once()
            ->andReturnNull();

        $this->output->shouldReceive()
            ->writeln(Mockery::any());

        $this->service->shouldReceive()
            ->handleProjectDependency(Mockery::type(Dependency::class))
            ->times(50);

        $this->service->shouldReceive()
            ->saveReport(Mockery::type(Text::class))
            ->once();

        $command = new BugReportTestCommand($this->service, null, $lockfile);
        $command->execute($this->input, $this->output);
    }

    /**
     * @test
     */
    public function it_executes_for_an_existing_composer_lock_file_when_provided_as_argument()
    {
        $lockfile = getcwd() . '/tests/fixtures/composer.lock';

        $this->input->shouldReceive()
            ->getArgument('dependency')
            ->once()
            ->andReturn($lockfile);

        $this->output->shouldReceive()
            ->writeln(Mockery::any());

        $this->service->shouldReceive()
            ->handleProjectDependency(Mockery::type(Dependency::class))
            ->times(50);

        $this->service->shouldReceive()
            ->saveReport(Mockery::type(Text::class))
            ->once();

        $command = new BugReportTestCommand($this->service);
        $command->execute($this->input, $this->output);
    }

    /**
     * @test
     */
    public function it_executes_for_a_provided_dependency_with_html_option()
    {
        $this->input->shouldReceive()
            ->getArgument('dependency')
            ->once()
            ->andReturn('mockery/mockery');

        $this->input->shouldReceive()
            ->getOption('html')
            ->once()
            ->andReturn(true);

        $this->output->shouldReceive()
            ->writeln(Mockery::any());

        $this->service->shouldReceive()
            ->handleProjectDependency(Mockery::type(Dependency::class))
            ->once();

        $this->service->shouldReceive()
            ->saveReport(Mockery::type(Html::class))
            ->once();

        $command = new BugReportTestCommand($this->service);
        $command->execute($this->input, $this->output);
    }
}

class BugReportTestCommand extends BugReport
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        return parent::execute($input, $output);
    }
}
