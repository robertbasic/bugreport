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

    public function setup()
    {
        $this->issues = include __DIR__ . '/fixtures/issues_mockery_all.php';
    }

    /**
     * @test
     */
    public function it_counts_issues()
    {
        $stats = new Stats($this->issues);

        $this->assertSame(41, $stats->openIssues());
        $this->assertSame(0, $stats->closedIssues());
        $this->assertSame(9, $stats->pullRequests());
    }

    /**
     * @test
     */
    public function it_calculates_average_age_for_open_pull_requests()
    {
        $stats = new Stats($this->issues);

        $this->assertSame(330, $stats->pullRequestsAverageAge());
    }
}
