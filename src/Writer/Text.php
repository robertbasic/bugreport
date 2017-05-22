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
            $stats = $line['stats'];

            $lines .= str_repeat('#', 20) . PHP_EOL;
            $lines .= 'bugreport for ' . $dependency->url() . PHP_EOL;
            $lines .= str_repeat('#', 20) . PHP_EOL;

            $lines .= "Open issues: " . $stats->openIssues() . PHP_EOL;
            $lines .= "Open pull requests: " . $stats->pullRequests() . PHP_EOL;
            $lines .= "Oldest open issue: " . $stats->oldestOpenIssue() . " days" . PHP_EOL;
            $lines .= "Newest open issue: " . $stats->newestOpenIssue() . " days" . PHP_EOL;
            $lines .= "Average age of open issues: " . $stats->openIssuesAverageAge() . " days" . PHP_EOL;
            $lines .= "Average age of open pull requests: " . $stats->pullRequestsAverageAge() . " days" . PHP_EOL;
        }

        return $lines;
    }
}
