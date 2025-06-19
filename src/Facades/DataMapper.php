<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Facades;

use Illuminate\Support\Facades\Facade;
use Wundii\DataMapper\Interface\DataConfigInterface;
use Wundii\DataMapper\LaravelPackage\DataMapper as DataMapperService;

/**
 * @method static object request(\Illuminate\Http\Request $request, string $className, string[] $rootElementTree = [], bool $forceInstance = false)
 * @method static object|null tryRequest(\Illuminate\Http\Request $request, string $className, string[] $rootElementTree = [], bool $forceInstance = false)
 * @method static object setDataConfig(DataConfigInterface $dataConfig)
 * @method static object array(array<mixed> $data, string $className, string[] $rootElementTree = [], bool $forceInstance = false)
 * @method static object json(string $data, string $className, string[] $rootElementTree = [], bool $forceInstance = false)
 * @method static object neon(string $data, string $className, string[] $rootElementTree = [], bool $forceInstance = false)
 * @method static object object(object $data, string $className, string[] $rootElementTree = [], bool $forceInstance = false)
 * @method static object xml(string $data, string $className, string[] $rootElementTree = [], bool $forceInstance = false)
 * @method static object yaml(string $data, string $className, string[] $rootElementTree = [], bool $forceInstance = false)
 * @method static string|null getErrorMessage()
 * @method static \Wundii\DataMapper\LaravelPackage\Enum\MapStatusEnum getMapStatusEnum()
 */
class DataMapper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DataMapperService::class;
    }
}
