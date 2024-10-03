<?php

function db_connect()
{
    if (file_exists(__DIR__ . '/../.env') && (getenv('APP_ENV') !== false ? $_ENV['APP_ENV'] !== 'production' : true)) {
        $env = parse_ini_file(__DIR__ . '/../.env');
    } else {
        $env = $_ENV;
    }
    return new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME'], $env['DB_PORT']);
}
