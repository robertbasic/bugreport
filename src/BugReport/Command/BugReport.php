<?php
declare(strict_types=1);

namespace BugReport\Command;

use BugReport\Issues;
use BugReport\Project;
use Github\Client;
use Github\ResultPager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugReport extends Command
{
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

        $client = new Client();
        $pager = new ResultPager($client);
        $issueApi = $client->issue();

        $issues = new Issues($project, $pager, $issueApi);
        $issues();

        $output->writeln("Project: " . $project->url());
        $output->writeln("Open issues: " . $issues->open());
        $output->writeln("Closed issues: " . $issues->closed());
        $output->writeln("Open pull requests: " . $issues->pullRequests());
    }
}
