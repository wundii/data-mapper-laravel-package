<?php

return [
    'data_config' => [
        'approach' => \Wundii\DataMapper\Enum\ApproachEnum::SETTER,
        'accessible' => \Wundii\DataMapper\Enum\AccessibleEnum::PUBLIC,
        'class_map' => [
            \DateTimeInterface::class => \DateTime::class,
            // ... additional mappings can be added here
        ],
    ],
];