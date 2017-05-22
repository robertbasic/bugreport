<?php
declare(strict_types=1);

namespace BugReport\Writer;

class Text implements Writer
{
    public function write(array $report, string $filename)
    {
        $lines = $this->formatReport($report);

        file_put_contents($filename, $lines);
    }

    private function formatReport(array $report) : string
    {
        $lines = '';

        foreach ($report as $line) {
            $dependency = $line['dependency'];
            $openIssues = $line['open_issues'];

            $lines .= str_repeat('#', 20) . PHP_EOL;
            $lines .= 'bugreport for ' . $dependency->url() . PHP_EOL;
            $lines .= str_repeat('#', 20) . PHP_EOL;

            $lines .= "Open issues: " . $openIssues->openIssues() . PHP_EOL;
            $lines .= "Open pull requests: " . $openIssues->pullRequests() . PHP_EOL;
            $lines .= "Oldest open issue: " . $openIssues->oldestOpenIssue() . " days" . PHP_EOL;
            $lines .= "Newest open issue: " . $openIssues->newestOpenIssue() . " days" . PHP_EOL;
            $lines .= "Average age of open issues: " . $openIssues->openIssuesAverageAge() . " days" . PHP_EOL;
            $lines .= "Average age of open pull requests: " . $openIssues->pullRequestsAverageAge() . " days" . PHP_EOL;
        }

        return $lines;
    }
}
