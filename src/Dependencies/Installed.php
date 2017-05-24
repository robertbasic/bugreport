<?php
declare(strict_types=1);

namespace BugReport\Dependencies;

use Webmozart\Assert\Assert;

class Installed
{
    const TYPE_GIT = 'git';

    /**
     * @var string[]
     */
    private $projects = [];

    private function __construct(array $packages)
    {
        foreach ($packages as $package) {
            $userRepo = $this->getUserRepo($package);

            if ($userRepo) {
                $this->projects[] = $userRepo;
            }
        }
    }

    public static function fromComposerPackages(array $packages) : self
    {
        Assert::notEmpty($packages);

        return new self($packages);
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
