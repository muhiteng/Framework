<?php
namespace app\core;

use Dotenv\Dotenv;



$database = new Database();

$refresh_token_gateway = new RefreshTokenGateway($database, $_ENV["SECRET_KEY"]);

echo $refresh_token_gateway->deleteExpired(), "\n";