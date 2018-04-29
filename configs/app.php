<?php

return [
    'services' => [
        'base_url' => 'https://shipping.tiendanube.com/v1/',
        'address'  => [
            'method'        => \TiendaNube\Http\Verbs::GET,
            'content_type'  => 'application/json',
            'endpoint'      => 'address/{zip}'
        ],
    ]
];