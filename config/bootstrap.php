<?php

return [

    'shopify_api' => [
        'version' => '2021-01',
        'admin_path' => '/admin/api/'
    ],

    'export' => [
        'path' => RESOURCES . 'json' . DS,
        'file_type' => 'json',
        'lifetime' => 60 * 60 * 3, // Default 1800sec
    ],
];
