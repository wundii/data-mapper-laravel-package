<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Console\Commands;

use Illuminate\Console\Command;
use Wundii\DataMapper\LaravelPackage\DataMapperServiceProvider;

class GenerateConfigCommand extends Command
{
    protected $signature = 'data-mapper:publish-config';

    protected $description = 'Publish the data-mapper configuration file';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--provider' => DataMapperServiceProvider::class,
            '--tag' => 'config',
        ]);

        $this->info('Data-mapper configuration published successfully!');

        return 0;
    }
}
