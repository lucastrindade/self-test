<?php

return [
    'services' => [
        'base_url' => 'https://shipping.tiendanube.com/v1',
        'address'  => [
            'method'   => \TiendaNube\Http\Verbs::GET,
            'endpoint' => '/address/{zip}'
        ],
    ]
];