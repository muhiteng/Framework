<?php
namespace app\core\middlewares;

use Closure;
use app\core\Auth;
use app\core\Request;
use app\core\Response;
use app\core\Application;
use app\core\middlewares\BaseMiddleware;

class PermissionMiddleware extends BaseMiddleware
{
     public array $actions=[];

    public function __construct(array $actions)
    {
        $this->actions=$actions;
    }
   
    public function handle(Closure $next,Request $request)
    { 
          
         return $next($request);
    }
    public function execute()
    {
      
      // get list of [action=>permission] in the controller
      foreach($this->actions as $key=>$value)
      {
        // list actions which has a permissions to execute
        if($key===Application::$app->controller->action)
        {
          // check if user has a permission to execute action
          if(! in_array($value,Auth::$user_permissions))
          {
           
              //error
              $response=new Response();
              $response->setSuccess(true);
              $response->setHttpStatusCode(200);
              $response->addMessage('permission error');
              $response->addMessage( []);
              $response->send();
              exit;
          }
          
        }
     
      }
    
    }
    
}