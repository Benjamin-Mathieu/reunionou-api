<?php

return [
    "settings" => [
        'displayErrorDetails' => true,
        'dbconf' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'atelier2',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''

        ],
        'secrets' => 'efzgyiyfezuygffezfe56zfez654fez864',
        "cors" => [
            "methods" => 'GET,POST,PUT,DELETE,OPTIONS',
            "headers" => 'Origin,Authorization,Content-Type,Accept,WWW-Authenticate',
            "maxAge" => 3600,
            'credentials' => true,
        ]
    ]
];
