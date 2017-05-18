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
     */
    public function it_allows_letters_and_numbers()
    {
        $user = 'robertbasic86';

        $user = User::fromString($user);

        $this->assertSame('robertbasic86', (string) $user);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_does_not_allow_non_alpha_numeric()
    {
        $user = 'robert.basic86';

        $user = User::fromString($user);
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
