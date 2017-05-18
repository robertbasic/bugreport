<?php
declare(strict_types=1);

namespace BugReport\Command;

use BugReport\GitHub\Issues;
use BugReport\Project;
use BugReport\Stats;
use Github\Client;
use Github\ResultPager;
use Github\ResultPagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugReport extends Command
{
    public function __construct($name = null, Client $client = null, ResultPagerInterface $pager = null)
    {
        parent::__construct($name);

        if (!$client) {
            $client = new Client();
        }
        $this->client = $client;

        if (!$pager) {
            $pager = new ResultPager($this->client);
        }
        $this->pager = $pager;

        $this->issueApi = $this->client->issue();
    }

    protected function configure()
    {
        $this->setName('bugreport')
            ->setDescription('Create a bug report.')
            ->setHelp('bugreport user/repo')
            ->addArgument('dependency', InputArgument::REQUIRED, 'Project dependency or composer.json file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello.');

        $dependency = $input->getArgument('dependency');

        $project = Project::fromUserRepo($dependency);

        $issues = (new Issues($project, $this->pager, $this->issueApi))->fetch();
        $stats = new Stats($issues);

        $output->writeln("Project: " . $project->url());
        $output->writeln("Open stats: " . $stats->openIssues());
        $output->writeln("Closed stats: " . $stats->closedIssues());
        $output->writeln("Open pull requests: " . $stats->pullRequests());
    }
}
