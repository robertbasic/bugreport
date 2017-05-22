<?php
declare(strict_types=1);

namespace BugReport\Formatter;

interface Formatter
{
    public function format(array $reports) : string;
}
