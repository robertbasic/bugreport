<?php
declare(strict_types=1);

namespace BugReportTest\Service;

use BugReport\Service\BugReport;
use BugReport\Service\BugReportFactory;
use PHPUnit\Framework\TestCase;

class BugReportFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_bugreport_service()
    {
        $service = (new BugReportFactory())(__DIR__ . '/../fixtures/bugreport_config.json');

        $this->assertInstanceOf(BugReport::class, $service);
    }
}
