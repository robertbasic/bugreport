<?php
declare(strict_types=1);

namespace BugReport;

use Webmozart\Assert\Assert;

class User
{

    /**
     * @var string
     */
    private $user;

    private function __construct(string $user)
    {
        Assert::stringNotEmpty($user, "User must be provided!");

        $this->user = $user;
    }

    public static function fromString(string $user) : self
    {
        return new self($user);
    }

    public function __toString() : string
    {
        return $this->user;
    }
}
