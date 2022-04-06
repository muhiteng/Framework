<?php
require_once __DIR__ . '/../vendor/autoload.php';
use app\core\Application;
use app\controllers\SiteController;
use app\controllers\AuthController;


$app= new Application(dirname(__DIR__));

$app->router->get('/', [SiteController::class,'home']);
//$app->router->get('/', function(){return 'Hello';});
//$app->router->get('/contact', function(){return 'contact';});
//$app->router->get('/contact',  'Contact');
$app->router->get('/contact',  [SiteController::class,'Contact']);
$app->router->post('/contact', [SiteController::class,'handleContact']);

$app->router->get('/login', [AuthController::class,'login']);
$app->router->post('/login', [AuthController::class,'login']);

$app->router->get('/register', [AuthController::class,'register']);
$app->router->post('/register', [AuthController::class,'register']);
$app->run();