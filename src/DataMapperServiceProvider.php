<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage;

use Illuminate\Support\ServiceProvider;
use Wundii\DataMapper\DataConfig;
// use Wundii\DataMapper\DataMapper as BaseDataMapper;
use Wundii\DataMapper\LaravelPackage\Console\Commands\GenerateConfigCommand;


class DataMapperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/data-mapper.php',
            'data-mapper'
        );

        $this->app->singleton(DataConfig::class, function ($app) {
            $config = $app->get('config')->get('data-mapper.data_config', []);

            return new DataConfig(
                $config['approach'] ?? null,
                $config['accessible'] ?? null,
                $config['class_map'] ?? []
            );
        });

        $this->app->singleton(DataMapper::class, function ($app) {

            $dataConfig = $app->make(DataConfig::class);

            return new DataMapper($dataConfig);
        });

        $this->app->alias(DataMapper::class, 'data-mapper');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/data-mapper.php' => $this->app->configPath
            ('data-mapper.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateConfigCommand::class,
            ]);
        }
    }
}
