<?php
declare(strict_types=1);

namespace BugReport\Formatter;

class Html implements Formatter
{
    public function format(array $report) : string
    {
        $lines = $this->top();

        foreach ($report as $line) {
            $dependency = $line['dependency'];
            $openIssues = $line['open_issues'];

            $lines .= "<tr>";
            $lines .= "<td>" . $dependency->url() . "</td>";
            $lines .= "<td>" . $openIssues->openIssues() . "</td>";
            $lines .= "<td>" . $openIssues->pullRequests() . "</td>";
            $lines .= "<td>" . $openIssues->oldestOpenIssue() . " days</td>";
            $lines .= "<td>" . $openIssues->newestOpenIssue() . " days</td>";
            $lines .= "<td>" . $openIssues->openIssuesAverageAge() . " days</td>";
            $lines .= "<td>" . $openIssues->pullRequestsAverageAge() . " days</td>";
            $lines .= "</tr>";
        }

        return $lines . $this->bottom();
    }

    private function top() : string
    {
        return <<<'TOP'
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>bugreport</title>
<meta name="description" content="bugreport">
<meta name="author" content="Robert Basic">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
</head>
<body>
<div class="container-fluid">
<div class="row">
<div class="col-sm-12 col-md-12 main">
<h2>bugreport</h2>
<div class="table-responsive">
<table class="table table-striped" id='bugreport'>
<thead>
<tr>
<th>Dependency</th>
<th># of open issues</th>
<th># of open PRs</th>
<th>Avg. age of open issues</th>
<th>Avg. age of open PRs</th>
<th>Age of oldest open issue</th>
<th>Age of newest open issue</th>
</tr>
</thead>
<tbody>
TOP;
    }

    private function bottom() : string
    {
        return <<<'BOT'
</tbody>
</table>
</div>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<script>$(document).ready(function() {$('#bugreport').DataTable({"order": [[1, "desc"]]});});</script>
</body>
</html>
BOT;
    }
}
