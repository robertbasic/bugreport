<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_project_from_user_repo_string()
    {
        $userRepo = 'robertbasic/bugreport';

        $project = Project::fromUserRepo($userRepo);

        $this->assertSame('https://github.com/robertbasic/bugreport', $project->url());
        $this->assertSame('robertbasic', (string) $project->user());
        $this->assertSame('bugreport', (string) $project->repo());
    }
}
