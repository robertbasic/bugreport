<?php
declare(strict_types=1);

namespace BugReportTest\Dependencies;

use BugReport\Dependencies\Installed;
use PHPUnit\Framework\TestCase;

class InstalledTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_all_projects_of_git_type_from_composer_packages()
    {
        $lockfile = __DIR__ . '/../fixtures/composer.lock';
        $lockfile = json_decode(file_get_contents($lockfile), true);
        $packages = array_merge($lockfile['packages'], $lockfile['packages-dev']);

        $installedDependencies = Installed::fromComposerPackages($packages);

        $expected = [
            "clue/php-stream-filter",
            "guzzle/guzzle",
            "guzzle/promises",
            "guzzle/psr7",
            "KnpLabs/php-github-api",
            "php-http/cache-plugin",
            "php-http/client-common",
            "php-http/discovery",
            "php-http/guzzle6-adapter",
            "php-http/httplug",
            "php-http/message",
            "php-http/message-factory",
            "php-http/promise",
            "php-fig/cache",
            "php-fig/http-message",
            "php-fig/log",
            "symfony/console",
            "symfony/debug",
            "symfony/options-resolver",
            "symfony/polyfill-mbstring",
            "webmozart/assert",
            "doctrine/instantiator",
            "hamcrest/hamcrest-php",
            "mockery/mockery",
            "myclabs/DeepCopy",
            "phar-io/manifest",
            "phar-io/version",
            "phpDocumentor/ReflectionCommon",
            "phpDocumentor/ReflectionDocBlock",
            "phpDocumentor/TypeResolver",
            "phpspec/prophecy",
            "sebastianbergmann/php-code-coverage",
            "sebastianbergmann/php-file-iterator",
            "sebastianbergmann/php-text-template",
            "sebastianbergmann/php-timer",
            "sebastianbergmann/php-token-stream",
            "sebastianbergmann/phpunit",
            "sebastianbergmann/phpunit-mock-objects",
            "sebastianbergmann/code-unit-reverse-lookup",
            "sebastianbergmann/comparator",
            "sebastianbergmann/diff",
            "sebastianbergmann/environment",
            "sebastianbergmann/exporter",
            "sebastianbergmann/global-state",
            "sebastianbergmann/object-enumerator",
            "sebastianbergmann/object-reflector",
            "sebastianbergmann/recursion-context",
            "sebastianbergmann/resource-operations",
            "sebastianbergmann/version",
            "theseer/tokenizer",
        ];

        $this->assertSame($expected, $installedDependencies->all());
        $this->assertSame(50, $installedDependencies->total());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_cannot_get_installed_dependencies_from_no_packages()
    {
        Installed::fromComposerPackages([]);
    }
}
