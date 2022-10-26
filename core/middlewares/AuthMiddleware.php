<?php
namespace app\core\middlewares;

use Closure;
use app\core\Auth;
use app\core\JWTCode;
use app\core\Request;
use app\core\Database;
use app\core\Response;
use app\models\UserGateway;
use app\core\middlewares\BaseMiddleware;

class AuthMiddleware extends BaseMiddleware
{
    public function handle(Closure $next,Request $request)
    {
      
        $database=new Database();
        $user_gateway=new UserGateway($database);
        $jwt_code=new JWTCode($_ENV['SECRET_KEY']);

        $auth=new Auth($user_gateway,$jwt_code);

        // check if user token is valid
        if ( ! $auth->authenticateAccessToken()) {
        
          $response=new Response();
          $response->setSuccess(true);
          $response->setHttpStatusCode(200);
          $response->addMessage('auth error');
          $response->addMessage( []);
          $response->send();
          exit;
      }
            
         return $next($request);
    }  //end of handle
    
} //end of class