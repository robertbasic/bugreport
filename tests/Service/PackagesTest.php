<?php
declare(strict_types=1);

namespace BugReportTest\Service;

use BugReport\Service\Packages;
use PHPUnit\Framework\TestCase;

class PackagesTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_all_projects_of_git_type_from_composer_lock_file()
    {
        $lockfile = __DIR__ . '/../fixtures/composer.lock';

        $packages = Packages::fromComposerLockFile($lockfile);

        $this->assertSame(51, count($packages->packages()));
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_cannot_get_installed_dependencies_from_a_non_existent_composer_lock_file()
    {
        Packages::fromComposerLockFile('non-existent/composer.lock');
    }
}
