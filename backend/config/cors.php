<?

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'web/*'], // adiciona as rotas que você tá usando        
    'exposed_headers' => [],
    'paths' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],

    'max_age' => 0,

    'supports_credentials' => true,

];

