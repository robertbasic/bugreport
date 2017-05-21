<?php
declare(strict_types=1);

namespace BugReport\Command;

use BugReport\Dependency;
use BugReport\InstalledDependencies;
use BugReport\Service\BugReport as BugReportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
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
        $output->writeln('bugreport v' . BugReportService::VERSION);

        $dependency = $input->getArgument('dependency');

        if (!is_null($dependency)) {
            $output->writeln('Getting bugreport for ' . $dependency);

            $this->handleProjectDependency($dependency, $output);

            $output->writeln('Done.');

            return;
        }

        $this->handleProjectDependencies($output);
    }

    protected function handleProjectDependencies(OutputInterface $output)
    {
        $dependencies = InstalledDependencies::fromComposerLockFile($this->lockfile);

        $output->writeln('Getting bugreport for ' . $dependencies->total() . ' installed dependencies');

        $progress = new ProgressBar($output, $dependencies->total());
        $progress->start();

        foreach ($dependencies->all() as $dependency) {
            $this->handleProjectDependency($dependency, $output);

            $progress->advance();
        }

        $progress->finish();

        $output->writeln('');
        $output->writeln('Done.');
    }

    protected function handleProjectDependency(string $dependency, OutputInterface $output)
    {
        $dependency = Dependency::fromUserRepo($dependency);

        $this->bugreport->handleProjectDependency($dependency);
    }
}
