<?php
require_once __DIR__ . '/../vendor/autoload.php';
 
use app\core\Response;

//Start test rsponse 
$response=new Response();
$response->setSuccess(true);
$response->setHttpStatusCode(200);
$response->addMessage('Message test 1');
$response->addMessage('Message test 2');
$response->send();
//End test rsponse 

