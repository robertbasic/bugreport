<?php
declare(strict_types=1);

namespace BugReportTest\Command;

use BugReport\Command\BugReport;
use Github\Api\ApiInterface;
use Github\Client;
use Github\ResultPagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugReportTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

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

    /**
     * @var BugReport
     */
    private $command;

    public function setup()
    {
        $this->input = Mockery::mock(InputInterface::class);
        $this->output = Mockery::mock(OutputInterface::class);

        $this->client = Mockery::mock(Client::class);
        $this->pager = Mockery::mock(ResultPagerInterface::class);
        $this->issueApi = Mockery::mock(ApiInterface::class);

        $this->client->shouldReceive()
            ->issue()
            ->andReturn($this->issueApi);

        $this->command = new BugReportTestCommand('bugreport', $this->client, $this->pager);
    }

    /**
     * @test
     */
    public function it_executes()
    {
        $this->input->shouldReceive()
            ->getArgument('dependency')
            ->once()
            ->andReturn('mockery/mockery');

        $this->output->shouldReceive()
            ->writeln(Mockery::any());

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

        $this->command->execute($this->input, $this->output);
    }
}

class BugReportTestCommand extends BugReport
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        return parent::execute($input, $output);
    }
}
