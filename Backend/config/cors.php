<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Allow all origins for testing
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*', 'X-Requested-With', 'Authorization', 'Accept', 'Content-Type'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];