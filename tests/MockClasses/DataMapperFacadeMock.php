<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Tests\MockClasses;

use Wundii\DataMapper\LaravelPackage\Facades\DataMapper;

class DataMapperFacadeMock extends DataMapper
{
    public static function getFacadeAccessor(): string
    {
        return parent::getFacadeAccessor();
    }
}
