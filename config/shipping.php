<?php

return [
    'default' => env('SHIPPING_DRIVER', 'tipax'),

    'drivers' => [
        'tipax' => [
            'base_url'    => env('TIPAX_API_URL', 'https://etipaxco.com/api'),
            'username'    => env('TIPAX_USERNAME'),
            'password'    => env('TIPAX_PASSWORD'),
            'client_id'   => env('TIPAX_CLIENT_ID'),
            'client_name' => env('TIPAX_CLIENT_NAME'),
            'secret'      => env('TIPAX_SECRET'),
            'scope'       => env('TIPAX_SCOPE', ''),
            'timeout'     => env('TIPAX_TIMEOUT', 30),
        ],
        'post' => [
            'base_url' => env('POST_API_URL'),
            'api_key'  => env('POST_API_KEY'),
            'timeout'  => env('POST_TIMEOUT', 30),
        ],
    ],

    'defaults' => [
        'currency'     => 'IRR',
        'service_type' => 'normal',
    ],
];
