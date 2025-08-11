<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (!array_key_exists('APP_ENV', $_SERVER)) {
    $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] ?? null;
}

if ($_SERVER['APP_ENV'] != 'prod') {
    if (!class_exists(Dotenv::class)) {
        throw new RuntimeException('The "APP_ENV" environment variable has not been found. Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
    }

    $path = dirname(__DIR__) . '/.env';
    $env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? '';
    $environment = file_exists("$path.$env") ? "$path.$env" : $path;
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

$_SERVER += $_ENV;
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) ?: 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? 'prod' !== $_SERVER['APP_ENV'];