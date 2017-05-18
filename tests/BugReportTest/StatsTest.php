<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\Stats;
use PHPUnit\Framework\TestCase;

class StatsTest extends TestCase
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

        $stats = new Stats($issues);

        $this->assertSame(41, $stats->openIssues());
        $this->assertSame(0, $stats->closedIssues());
        $this->assertSame(9, $stats->pullRequests());
    }
}
