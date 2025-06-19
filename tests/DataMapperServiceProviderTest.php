<?php

declare(strict_types=1);

namespace Wundii\DataMapper\Tests;

use Orchestra\Testbench\TestCase;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper;
use Wundii\DataMapper\LaravelPackage\DataMapperServiceProvider;

class DataMapperServiceProviderTest extends TestCase
{
    public function testItRegistersDataConfigSingleton(): void
    {
        $dataConfig = $this->app->make(DataConfig::class);
        $this->assertInstanceOf(DataConfig::class, $dataConfig);
    }

    public function testItRegistersDataMapperSingleton(): void
    {
        $dataMapper = $this->app->make(DataMapper::class);
        $this->assertInstanceOf(DataMapper::class, $dataMapper);
    }

    public function testItAliasesDataMapper(): void
    {
        $dataMapper = $this->app->make('data-mapper');
        $this->assertInstanceOf(DataMapper::class, $dataMapper);
    }

    protected function getPackageProviders($app)
    {
        return [DataMapperServiceProvider::class];
    }
}
