<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    /**
     * @test
     * @dataProvider validUserRepos
     */
    public function it_creates_a_project_from_user_repo_string($userRepo, $url, $user, $repo)
    {
        $project = Project::fromUserRepo($userRepo);

        $this->assertSame($url, $project->url());
        $this->assertSame($user, (string) $project->user());
        $this->assertSame($repo, (string) $project->repo());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @dataProvider invalidUserRepos
     */
    public function it_cannot_create_a_project_from_what_does_not_look_like_a_user_repo($userRepo)
    {
        Project::fromUserRepo($userRepo);
    }

    public function validUserRepos()
    {
        return [
            [
                'robertbasic/bugreport',
                'https://github.com/robertbasic/bugreport',
                'robertbasic',
                'bugreport',
            ],
            [
                'robertbasic86/bugreport',
                'https://github.com/robertbasic86/bugreport',
                'robertbasic86',
                'bugreport',
            ],
            [
                'robertbasic/bugreport42',
                'https://github.com/robertbasic/bugreport42',
                'robertbasic',
                'bugreport42',
            ],
        ];
    }

    public function invalidUserRepos()
    {
        return [
            [''],
            ['robertbasic'],
            ['bug.report'],
            ['robert basic'],
            ['robertbasic/'],
            ['/bugreport'],
            ['robertbasic-bugreport'],
            ['robert.basic/bugreport'],
            ['robertbasic/bug.report'],
        ];
    }
}
