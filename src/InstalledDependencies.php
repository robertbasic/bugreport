<?php
declare(strict_types=1);

namespace BugReport;

use Webmozart\Assert\Assert;

class InstalledDependencies
{

    /**
     * @var string
     */
    private $lockfile;

    /**
     * @var int
     */
    private $totalDependencies = 0;

    private function __construct(string $lockfile)
    {
        $this->lockfile = $lockfile;
    }

    public static function fromComposerLockFile(string $lockfile) : self
    {
        Assert::fileExists($lockfile);

        return new self($lockfile);
    }

    public function total() : int
    {
        return $this->totalDependencies;
    }
}
