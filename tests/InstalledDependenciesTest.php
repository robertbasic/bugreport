<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\InstalledDependencies;
use PHPUnit\Framework\TestCase;

class InstalledDependenciesTest extends TestCase
{
    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_cannot_get_installed_dependencies_from_a_non_existent_composer_lock_file()
    {
        InstalledDependencies::fromComposerLockFile('non-existent/composer.lock');
    }
}
