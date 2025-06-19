<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Tests;

use Orchestra\Testbench\TestCase;
use Wundii\DataMapper\LaravelPackage\Console\Commands\GenerateConfigCommand;
use Wundii\DataMapper\LaravelPackage\DataMapperServiceProvider;

class GenerateConfigCommandTest extends TestCase
{
    public function testHandleCallsVendorPublishAndOutputsInfo(): void
    {
        $command = $this->getMockBuilder(GenerateConfigCommand::class)
            ->onlyMethods(['call', 'info'])
            ->getMock();

        $command->expects($this->once())
            ->method('call')
            ->with(
                'vendor:publish',
                [
                    '--provider' => DataMapperServiceProvider::class,
                    '--tag' => 'config',
                ]
            )
            ->willReturn(0);

        $command->expects($this->once())
            ->method('info')
            ->with('Data-mapper configuration published successfully!');

        $result = $command->handle();
        $this->assertSame(0, $result);
    }
}
