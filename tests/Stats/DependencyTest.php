<?php
declare(strict_types=1);

namespace BugReportTest\Stats;

use BugReport\Stats\Dependency;
use PHPUnit\Framework\TestCase;

class DependencyTest extends TestCase
{
    /**
     * @var array
     */
    private $issues;

    /**
     * @var \DateTimeImmutable
     */
    private $since;

    public function setup()
    {
        $this->issues = include __DIR__ . '/../fixtures/issues_mockery_all.php';
        $this->since = new \DateTimeImmutable('2017-05-19 09:00:00Z', new \DateTimeZone('UTC'));
    }

    /**
     * @test
     */
    public function it_counts_issues()
    {
        $stats = new Dependency($this->issues, $this->since);

        $this->assertSame(41, $stats->openIssues());
        $this->assertSame(9, $stats->pullRequests());
    }

    /**
     * @test
     */
    public function it_calculates_age_of_oldest_open_issues()
    {
        $stats = new Dependency($this->issues, $this->since);

        $this->assertSame(1995, $stats->oldestOpenIssue());
    }

    /**
     * @test
     */
    public function it_calculates_age_of_newest_open_issues()
    {
        $stats = new Dependency($this->issues, $this->since);

        $this->assertSame(4, $stats->newestOpenIssue());
    }

    /**
     * @test
     */
    public function it_calculates_average_age_for_open_issues()
    {
        $stats = new Dependency($this->issues, $this->since);

        $this->assertSame(621, $stats->openIssuesAverageAge());
    }

    /**
     * @test
     */
    public function it_calculates_average_age_for_open_pull_requests()
    {
        $stats = new Dependency($this->issues, $this->since);

        $this->assertSame(330, $stats->pullRequestsAverageAge());
    }

    /**
     * @test
     */
    public function it_skips_closed_issues()
    {
        $issues = [
            [
                // Don't care about the rest
                'state' => 'closed',
            ],
        ];

        $stats = new Dependency($issues, $this->since);

        $this->assertSame(0, $stats->openIssues());
    }
}
