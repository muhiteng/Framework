<?php
require_once __DIR__ . '/../vendor/autoload.php';
use app\core\Application;

$app= new Application();

$app->router->get('/', 'home');
//$app->router->get('/', function(){return 'Hello';});
//$app->router->get('/contact', function(){return 'contact';});
$app->router->get('/contact', 'contact');
$app->run();