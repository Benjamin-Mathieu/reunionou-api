<?php

return [
    "settings" => [
        'displayErrorDetails' => true,
        'dbconf'=>[
            'driver' => 'mysql',
            'host' => 'db',
            'database' => 'events',
            'username'  => 'events',
            'password'  => 'events',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''

        ],
        'secrets' => 'efzgyiyfezuygffezfe56zfez654fez864',
        "cors" =>[
            "methods" => 'GET,POST,PUT,DELETE,OPTIONS',
            "headers" => 'Origin,Authorization,Content-Type,Accept,WWW-Authenticate',
            "maxAge" => 3600
        ]
    ]
];
