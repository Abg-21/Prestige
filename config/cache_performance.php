<?php

return [
    'default' => env('CACHE_DRIVER', 'file'),
    'stores' => [
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],
        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
            'lock_connection' => null,
        ],
        // Cache super rápido en memoria
        'runtime' => [
            'driver' => 'array',
            'serialize' => false,
        ],
    ],
    'prefix' => env('CACHE_PREFIX', 'prestige'),
    // Configuraciones de rendimiento
    'ttl' => [
        'candidatos' => 300, // 5 minutos
        'empleados' => 300,  // 5 minutos
        'puestos' => 3600,   // 1 hora
        'usuarios' => 1800,  // 30 minutos
    ]
];