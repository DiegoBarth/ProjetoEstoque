<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'web/*'],
    'exposed_headers' => [],
    'allowed_origins' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'max_age' => 0,
    'supports_credentials' => true,

];

