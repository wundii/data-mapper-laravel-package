<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Tests;

use Orchestra\Testbench\TestCase;
use Wundii\DataMapper\LaravelPackage\DataMapper as DataMapperService;
use Wundii\DataMapper\LaravelPackage\Tests\MockClasses\DataMapperFacadeMock;

class DataMapperFacadeTest extends TestCase
{
    public function testFacadeAccessor(): void
    {
        $accessor = DataMapperFacadeMock::getFacadeAccessor();

        $this->assertSame(DataMapperService::class, $accessor);
    }
}
