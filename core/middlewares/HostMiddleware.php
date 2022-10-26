<?php
namespace app\core\middlewares;
use app\core\middlewares\BaseMiddleware;
use app\core\Application;
use app\core\Request;
use app\core\exception\AccessDenied;
use Closure;

class HostMiddleware extends BaseMiddleware
{
    

    public function __construct()
    {
        
    }
    public function handle(Closure $next,Request $request)
    { 
        
        
        $request->id=23;
        return $next($request);
    }
    public function execute()
    {
       var_dump($_SERVER['REQUEST_URI']);
                throw new AccessDenied();
               
           
    }
    
}