<?php
declare(strict_types=1);

namespace BugReportTest\Formatter;

use BugReport\Formatter\Html;
use BugReport\Stats\OpenIssues;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class HtmlTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function formatting_in_html()
    {
        $dependency = Mockery::mock(Dependency::class);
        $openIssues = Mockery::mock(OpenIssues::class);

        $report = [[
            'dependency' => $dependency,
            'open_issues' => $openIssues,
        ]];

        $dependency->shouldReceive()
            ->url()
            ->once()
            ->ordered()
            ->andReturn('https://github.com/mockery/mockery');
        $dependency->shouldReceive()
            ->shortUrl()
            ->once()
            ->ordered()
            ->andReturn('mockery/mockery');

        $openIssues->shouldReceive()
            ->openIssues()
            ->once()
            ->ordered()
            ->andReturn(0);
        $openIssues->shouldReceive()
            ->pullRequests()
            ->once()
            ->ordered()
            ->andReturn(0);
        $openIssues->shouldReceive()
            ->openIssuesAverageAge()
            ->once()
            ->ordered()
            ->andReturn(0);
        $openIssues->shouldReceive()
            ->pullRequestsAverageAge()
            ->once()
            ->ordered()
            ->andReturn(0);
        $openIssues->shouldReceive()
            ->oldestOpenIssue()
            ->once()
            ->ordered()
            ->andReturn(0);
        $openIssues->shouldReceive()
            ->newestOpenIssue()
            ->once()
            ->ordered()
            ->andReturn(0);

        $formatter = new Html();
        $formatter->format($report);
    }
}
