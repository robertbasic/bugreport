<?php
declare(strict_types=1);

namespace BugReport;

use Webmozart\Assert\Assert;

class InstalledDependencies
{
    const TYPE_GIT = 'git';

    /**
     * @var Project[]
     */
    private $projects = [];

    private function __construct(string $lockfile)
    {
        $lockfile = json_decode(file_get_contents($lockfile), true);

        $packages = array_merge($lockfile['packages'], $lockfile['packages-dev']);

        foreach ($packages as $package) {
            $userRepo = $this->getUserRepo($package);

            if ($userRepo) {
                $this->projects[] = $userRepo;
            }
        }
    }

    public static function fromComposerLockFile(string $lockfile) : self
    {
        Assert::fileExists($lockfile);

        return new self($lockfile);
    }

    public function all() : array
    {
        return $this->projects;
    }

    public function total() : int
    {
        return count($this->projects);
    }

    private function getUserRepo(array $package) : string
    {
        if ($package['source']['type'] === self::TYPE_GIT) {
            return substr(ltrim(parse_url($package['source']['url'], PHP_URL_PATH), '/'), 0, -4);
        }

        return '';
    }
}
