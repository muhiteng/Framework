<?php

use app\core\Application;
use app\controllers\AuthController; 
use app\controllers\RolesPermissionsController;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$app= new Application(dirname(__DIR__));

$app->router->post('/login', [AuthController::class,'login']);//->permissionmiddlware('role:admin');;
$app->router->post('/register', [AuthController::class,'register']);

// web means group of middlewares whick be default
$app->router->get('/profile', [AuthController::class,'profile'])
->middlware('auth')
->rolemiddlware('role:user');

$app->router->get('/roles', [RolesPermissionsController::class,'getAllRoles'])
->middlware('auth')
->rolemiddlware('role:user');

$app->router->post('/roles_create', [RolesPermissionsController::class,'create'])
->middlware('auth')
->rolemiddlware('role:user');

$app->router->post('/refresh', [AuthController::class,'refresh_token']);
$app->router->post('/log_out', [AuthController::class,'log_out']);

$app->run();