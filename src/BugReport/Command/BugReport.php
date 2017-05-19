<?php
declare(strict_types=1);

namespace BugReport\Command;

use BugReport\GitHub\Issues;
use BugReport\Project;
use BugReport\Stats\Dependency as DependencyStats;
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
            ->addArgument('dependency', InputArgument::OPTIONAL, 'Project dependency or composer.json file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dependency = $input->getArgument('dependency');

        if (!is_null($dependency)) {
            return $this->handleProjectDependency($dependency, $output);
        }

        $this->handleProjectDependencies($output);
    }

    protected function handleProjectDependencies(OutputInterface $output)
    {
        $lockfile = getcwd() . DIRECTORY_SEPARATOR . 'composer.lock';

        if (!is_file($lockfile)) {
            $output->writeln('No composer.lock file found in ' . getcwd());
            return;
        }
    }

    protected function handleProjectDependency(string $dependency, OutputInterface $output)
    {
        $project = Project::fromUserRepo($dependency);

        $output->writeln('Getting bugreport for ' . $project->url());

        $issues = (new Issues($project, $this->pager, $this->issueApi))->fetch();
        $stats = new DependencyStats($issues);

        $output->writeln("Open issues: " . $stats->openIssues());
        $output->writeln("Open pull requests: " . $stats->pullRequests());
        $output->writeln("Oldest open issue: " . $stats->oldestOpenIssue() . " days");
        $output->writeln("Newest open issue: " . $stats->newestOpenIssue() . " days");
        $output->writeln("Average age of open issues: " . $stats->openIssuesAverageAge() . " days");
        $output->writeln("Average age of open pull requests: " . $stats->pullRequestsAverageAge() . " days");
    }
}
