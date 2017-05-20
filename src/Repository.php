<?php
declare(strict_types=1);

namespace BugReport;

use Webmozart\Assert\Assert;

class Repository
{

    /**
     * @var string
     */
    private $repository;

    private function __construct(string $repository)
    {
        Assert::stringNotEmpty($repository, "Repository must be provided!");

        $this->repository = $repository;
    }

    public static function fromString(string $repository) : self
    {
        return new self($repository);
    }

    public function __toString() : string
    {
        return $this->repository;
    }
}
