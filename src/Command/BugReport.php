<?php
declare(strict_types=1);

namespace BugReport\Command;

use BugReport\Service\BugReport as BugReportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugReport extends Command
{

    /**
     * @var string
     */
    private $lockfile;

    /**
     * @var BugReportService
     */
    private $bugreport;

    public function __construct(BugReportService $bugreport, $name = null, string $lockfile = null)
    {
        parent::__construct($name);

        $this->bugreport = $bugreport;

        if (!$lockfile) {
            $lockfile = getcwd() . DIRECTORY_SEPARATOR . 'composer.lock';
        }
        $this->lockfile = $lockfile;
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
        $this->bugreport->handleProjectDependencies($this->lockfile);

        $output->writeln($this->bugreport->getReportLines());
    }

    protected function handleProjectDependency(string $dependency, OutputInterface $output)
    {
        $this->bugreport->handleProjectDependency($dependency);

        $output->writeln($this->bugreport->getReportLines());
    }
}
