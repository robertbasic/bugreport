<?php
declare(strict_types=1);

namespace BugReportTest\Stats;

use BugReport\Stats\OpenIssues;
use PHPUnit\Framework\TestCase;

class OpenIssuesTest extends TestCase
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
        $openIssues = new OpenIssues($this->issues, $this->since);

        $this->assertSame(41, $openIssues->openIssues());
        $this->assertSame(9, $openIssues->pullRequests());
    }

    /**
     * @test
     */
    public function it_calculates_age_of_oldest_open_issues()
    {
        $openIssues = new OpenIssues($this->issues, $this->since);

        $this->assertSame(1995, $openIssues->oldestOpenIssue());
    }

    /**
     * @test
     */
    public function it_calculates_age_of_newest_open_issues()
    {
        $openIssues = new OpenIssues($this->issues, $this->since);

        $this->assertSame(4, $openIssues->newestOpenIssue());
    }

    /**
     * @test
     */
    public function it_calculates_average_age_for_open_issues()
    {
        $openIssues = new OpenIssues($this->issues, $this->since);

        $this->assertSame(621, $openIssues->openIssuesAverageAge());
    }

    /**
     * @test
     */
    public function it_calculates_average_age_for_open_pull_requests()
    {
        $openIssues = new OpenIssues($this->issues, $this->since);

        $this->assertSame(330, $openIssues->pullRequestsAverageAge());
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

        $openIssues = new OpenIssues($issues, $this->since);

        $this->assertSame(0, $openIssues->openIssues());
    }
}
