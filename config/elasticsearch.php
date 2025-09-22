<?php

return [
   'hosts' => [
        env('ELASTICSEARCH_HOST', 'localhost:9200'),
    ],
    'ssl_verification' => env('ELASTICSEARCH_SSL_VERIFICATION', false),
    // 7 или 8 — должна совпадать с версией кластера для совместимых заголовков
    'compat_version' => env('ELASTICSEARCH_COMPAT_VERSION', 8),
];