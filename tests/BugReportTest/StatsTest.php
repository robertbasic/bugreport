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
     * @var \DateTimeImmutable
     */
    private $since;

    public function setup()
    {
        $this->issues = include __DIR__ . '/fixtures/issues_mockery_all.php';
        $this->since = new \DateTimeImmutable('2017-05-19 09:00:00Z', new \DateTimeZone('UTC'));
    }

    /**
     * @test
     */
    public function it_counts_issues()
    {
        $stats = new Stats($this->issues, $this->since);

        $this->assertSame(41, $stats->openIssues());
        $this->assertSame(9, $stats->pullRequests());
    }

    /**
     * @test
     */
    public function it_calculates_age_of_oldest_open_issues()
    {
        $stats = new Stats($this->issues, $this->since);

        $this->assertSame(1995, $stats->oldestOpenIssue());
    }

    /**
     * @test
     */
    public function it_calculates_age_of_newest_open_issues()
    {
        $stats = new Stats($this->issues, $this->since);

        $this->assertSame(4, $stats->newestOpenIssue());
    }

    /**
     * @test
     */
    public function it_calculates_average_age_for_open_issues()
    {
        $stats = new Stats($this->issues, $this->since);

        $this->assertSame(621, $stats->openIssuesAverageAge());
    }

    /**
     * @test
     */
    public function it_calculates_average_age_for_open_pull_requests()
    {
        $stats = new Stats($this->issues, $this->since);

        $this->assertSame(330, $stats->pullRequestsAverageAge());
    }
}
