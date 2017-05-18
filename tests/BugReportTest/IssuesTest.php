<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\Issues;
use PHPUnit\Framework\TestCase;

class IssuesTest extends TestCase
{
    /**
     * @var Issues
     */
    private $issues;

    /**
     * @test
     */
    public function it_counts_issues()
    {
        $issues = include __DIR__ . '/fixtures/issues_mockery_all.php';

        $issues = new Issues($issues);

        $this->assertSame(41, $issues->open());
        $this->assertSame(0, $issues->closed());
        $this->assertSame(9, $issues->pullRequests());
    }
}
