<?php

return [
    'domain' => env('HORIZON_DOMAIN'),

    'path' => env('HORIZON_PATH', 'horizon'),

    'use' => 'default',

    'middleware' => ['web'],

    'prefix' => env('HORIZON_PREFIX'),

    'waits' => [
        'redis:default' => 60,
        'redis:media' => 60,
        'redis:imports' => 60,
    ],

    'trim' => [
        'recent' => 60,
        'failed' => 10080,
        'monitored' => 10080,
    ],

    'metrics' => [
        'trim_snapshots' => [
            'job' => 24,
            'queue' => 24,
        ],
    ],

    'fast_termination' => false,

    'memory_limit' => 64,

    'environments' => [
        'local' => [
            'supervisor-media' => [
                'connection' => 'redis',
                'queue' => ['media', 'imports', 'default'],
                'balance' => 'simple',
                'minProcesses' => 1,
                'maxProcesses' => 3,
                'tries' => 1,
            ],
        ],

        'production' => [
            'supervisor-media' => [
                'connection' => 'redis',
                'queue' => ['media', 'imports', 'default'],
                'balance' => 'auto',
                'minProcesses' => 1,
                'maxProcesses' => 10,
                'tries' => 1,
            ],
        ],
    ],
];


