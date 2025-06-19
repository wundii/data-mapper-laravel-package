<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Tests\MockClasses;

final class TypeString
{
    public function __construct(
        public string $string,
    ) {
    }
}
