<?php
namespace app\core\middlewares;
use app\core\middlewares\BaseMiddleware;
use app\core\Application;
use app\core\Request;
use app\core\exception\AccessDenied;
use Closure;

//class ProxyMiddleware extends BaseMiddleware
class ProxyMiddleware implements MiddlewareInterface
{
    

    public function __construct()
    {
        
    }
     function handle(Closure $next,Request $request)
    { 
        //  $next = function ($parameter) {
        //     return $parameter . ' This message is modified by Proxy middleware ';
        // };
        $request->name='majed';
        return $next($request);
        
    }
    public function execute()
    {
       var_dump($_SERVER['REQUEST_URI']);
                throw new AccessDenied();
               
           
    }
    
}