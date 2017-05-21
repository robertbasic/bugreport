<?php
declare(strict_types=1);

namespace BugReportTest\Service;

use BugReport\Service\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_no_config()
    {
        $config = new Config('non-existent.json');

        $this->assertFalse($config->hasConfig());
        $this->assertSame('', $config->githubPersonalAccessToken());
    }

    /**
     * @test
     */
    public function it_has_config()
    {
        $config = new Config(getcwd() . '/tests/fixtures/bugreport.json');

        $this->assertTrue($config->hasConfig());
        $this->assertSame('github-pat', $config->githubPersonalAccessToken());
    }
}
