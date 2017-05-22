<?php
declare(strict_types=1);

namespace BugReport\Writer;

interface Writer
{
    public function write(array $reports, string $filename);
}
