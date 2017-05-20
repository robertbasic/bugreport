<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_user_from_string()
    {
        $user = 'robertbasic';

        $user = User::fromString($user);

        $this->assertSame('robertbasic', (string) $user);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_cannot_create_a_user_from_empty_string()
    {
        $user = '';

        User::fromString($user);
    }
}
