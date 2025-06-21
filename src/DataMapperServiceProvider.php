<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper as BaseDataMapper;
use Wundii\DataMapper\Enum\AccessibleEnum;
use Wundii\DataMapper\Enum\ApproachEnum;
use Wundii\DataMapper\LaravelPackage\Console\Commands\GenerateConfigCommand;

class DataMapperServiceProvider extends ServiceProvider
{
    public const CONFIG_PATH = __DIR__ . '/../config/data-mapper.php';

    public function register(): void
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'data-mapper'
        );

        $this->app->singleton(
            DataConfig::class,
            function (Container $container): DataConfig {
                $configRepository = $container->get('config');
                $configRepository = $configRepository instanceof Repository ? $configRepository : new Repository([]);

                $dataConfig = $configRepository->get('data-mapper.data_config', []);
                $dataConfig = is_array($dataConfig) ? $dataConfig : [];

                $approachEnum = $dataConfig['approach'] instanceof ApproachEnum
                    ? $dataConfig['approach']
                    : ApproachEnum::SETTER;

                $accessibleEnum = $dataConfig['accessible'] instanceof AccessibleEnum
                    ? $dataConfig['accessible']
                    : AccessibleEnum::PUBLIC;

                /** @var string[] $classMap */
                $classMap = is_array($dataConfig['class_map']) ? $dataConfig['class_map'] : [];
                foreach ($classMap as $key => $value) {
                    if (! class_exists((string) $key) && ! interface_exists((string) $key)) {
                        unset($classMap[$key]);
                        continue;
                    }

                    if (! class_exists((string) $value) && ! interface_exists((string) $value)) {
                        unset($classMap[$key]);
                    }

                    $classMap[$key] = $value;
                }

                return new DataConfig(
                    $approachEnum,
                    $accessibleEnum,
                    $classMap,
                );
            }
        );

        $this->app->singleton(
            DataMapper::class,
            function (Container $container): DataMapper {
                $dataConfig = $container->make(DataConfig::class);

                return new DataMapper($dataConfig);
            }
        );

        $this->app->singleton(
            BaseDataMapper::class,
            function (Container $container): BaseDataMapper {
                $dataConfig = $container->make(DataConfig::class);

                return new BaseDataMapper($dataConfig);
            }
        );

        $this->app->alias(DataMapper::class, 'data-mapper');
    }

    public function boot(): void
    {
        $this->publishes([
            self::CONFIG_PATH => $this->app->configPath('data-mapper.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateConfigCommand::class,
            ]);
        }
    }
}
