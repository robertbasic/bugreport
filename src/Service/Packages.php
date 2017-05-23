<?php
declare(strict_types=1);

namespace BugReport\Service;

use Webmozart\Assert\Assert;

class Packages
{
    /**
     * @var array
     */
    private $packages;

    private function __construct(string $lockfile)
    {
        $lockfile = json_decode(file_get_contents($lockfile), true);

        $this->packages = array_merge($lockfile['packages'], $lockfile['packages-dev']);
    }

    public static function fromComposerLockFile(string $lockfile) : self
    {
        Assert::fileExists($lockfile);

        return new self($lockfile);
    }

    public function packages() : array
    {
        return $this->packages;
    }
}
