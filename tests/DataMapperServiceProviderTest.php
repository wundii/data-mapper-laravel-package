<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Tests;

use Orchestra\Testbench\TestCase;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper as DataMapperNative;
use Wundii\DataMapper\LaravelPackage\DataMapper as DataMapperLaravel;
use Wundii\DataMapper\LaravelPackage\DataMapperServiceProvider;

class DataMapperServiceProviderTest extends TestCase
{
    public function testItRegistersDataConfigSingleton(): void
    {
        $dataConfig = $this->app->make(DataConfig::class);
        $this->assertInstanceOf(DataConfig::class, $dataConfig);
    }

    public function testItRegistersDataMapperSingletonLaravel(): void
    {
        $dataMapper = $this->app->make(DataMapperLaravel::class);
        $this->assertInstanceOf(DataMapperLaravel::class, $dataMapper);
    }

    public function testItRegistersDataMapperSingletonNative(): void
    {
        $dataMapper = $this->app->make(DataMapperNative::class);
        $this->assertInstanceOf(DataMapperNative::class, $dataMapper);
    }

    public function testItAliasesDataMapperLaravel(): void
    {
        $dataMapper = $this->app->make('data-mapper');
        $this->assertInstanceOf(DataMapperLaravel::class, $dataMapper);
    }

    protected function getPackageProviders($app)
    {
        return [DataMapperServiceProvider::class];
    }
}
