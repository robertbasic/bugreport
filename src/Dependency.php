<?php
declare(strict_types=1);

namespace BugReport;

use Webmozart\Assert\Assert;

class Dependency
{
    const URL = 'https://github.com/%s/%s';

    /**
     * @var User
     */
    private $user;

    /**
     * @var Repository
     */
    private $repository;

    private function __construct(User $user, Repository $repo)
    {
        $this->user = $user;
        $this->repository = $repo;
    }

    public static function fromUserRepo(string $userRepo) : self
    {
        Assert::regex($userRepo, "/^[^\/]+\/[^\/]+$/i");

        list($user, $repo) = explode('/', $userRepo);

        $user = User::fromString($user);
        $repo = Repository::fromString($repo);

        return new self($user, $repo);
    }

    public function url() : string
    {
        return sprintf(self::URL, $this->user(), $this->repo());
    }

    public function user() : User
    {
        return $this->user;
    }

    public function repo() : Repository
    {
        return $this->repository;
    }
}
