<?php
declare(strict_types=1);

namespace BugReportTest;

use BugReport\Dependency;
use PHPUnit\Framework\TestCase;

class DependencyTest extends TestCase
{
    /**
     * @test
     * @dataProvider validUserRepos
     */
    public function it_creates_a_dependency_from_user_repo_string($userRepo, $url, $shortUrl, $user, $repo)
    {
        $dependency = Dependency::fromUserRepo($userRepo);

        $this->assertSame($url, $dependency->url());
        $this->assertSame($shortUrl, $dependency->shortUrl());
        $this->assertSame($user, (string) $dependency->user());
        $this->assertSame($repo, (string) $dependency->repo());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @dataProvider invalidUserRepos
     */
    public function it_cannot_create_a_dependency_from_what_does_not_look_like_a_user_repo($userRepo)
    {
        Dependency::fromUserRepo($userRepo);
    }

    public function validUserRepos()
    {
        return [
            [
                'robertbasic/bugreport',
                'https://github.com/robertbasic/bugreport',
                'robertbasic/bugreport',
                'robertbasic',
                'bugreport',
            ],
            [
                'robertbasic86/bugreport',
                'https://github.com/robertbasic86/bugreport',
                'robertbasic86/bugreport',
                'robertbasic86',
                'bugreport',
            ],
            [
                'robertbasic/bugreport42',
                'https://github.com/robertbasic/bugreport42',
                'robertbasic/bugreport42',
                'robertbasic',
                'bugreport42',
            ],
            [
                'robert-basic/bug-report42',
                'https://github.com/robert-basic/bug-report42',
                'robert-basic/bug-report42',
                'robert-basic',
                'bug-report42',
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
            ['robert/basic/bugreport'],
            ['robertbasic/bug/report'],
        ];
    }
}
