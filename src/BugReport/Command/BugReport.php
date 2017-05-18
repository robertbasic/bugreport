<?php
declare(strict_types=1);

namespace BugReport\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugReport extends Command
{
    protected function configure()
    {
        $this->setName('bugreport')
            ->setDescription('Create a bug report.')
            ->setHelp('bugreport user/repo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello.');
    }
}
