<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\Repository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_repository_from_string()
    {
        $repository = 'robertbasic';

        $repository = Repository::fromString($repository);

        $this->assertSame('robertbasic', (string) $repository);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_cannot_create_a_repository_from_empty_string()
    {
        $repository = '';

        Repository::fromString($repository);
    }
}
