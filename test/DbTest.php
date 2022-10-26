<?php
require_once __DIR__ . '/../vendor/autoload.php';
 
use app\core\Database;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$config=[
    'userClass'=> User::class,
    'db' => [
        'dsn'=>$_ENV['DB_DSN'],
        'user'=>$_ENV['DB_USER'],
        'password'=>$_ENV['DB_PASSWORD'],

    ]
];

$db= new Database($config['db']);
$db->createMigrationsTable();
