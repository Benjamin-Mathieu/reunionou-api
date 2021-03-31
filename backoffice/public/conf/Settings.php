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
        'secrets' => 'e7224ff583f6190231f036b13a48d6de',
        "cors" => [
            "methods" => 'GET,POST,PUT,DELETE,OPTIONS',
            "headers" => 'Origin,Authorization,Content-Type,Accept,WWW-Authenticate',
            "maxAge" => 3600,
            'credentials' => true,
        ]
    ]
];