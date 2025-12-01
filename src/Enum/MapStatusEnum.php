<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Enum;

enum MapStatusEnum: string
{
    case AWAIT = 'await';

    case ERROR = 'error';

    case SUCCESS = 'success';
}
