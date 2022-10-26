<?php
namespace app\core;

class Kernal
{

    // list of all middlewares
    protected array  $routeMiddleware = [
        'auth' => \app\core\middlewares\AuthMiddleware::class,
        'proxy' => \app\core\middlewares\ProxyMiddleware::class,
        'host' => \app\core\middlewares\HostMiddleware::class,
        
    ];

    // list of groups of middlewares
    protected array $middlewareGroups = [
        'web' => [
           
            \app\core\middlewares\ProxyMiddleware::class,
             
         \app\core\middlewares\HostMiddleware::class,
           
        ],
        'auth' => [
            \app\core\middlewares\AuthMiddleware::class,
        ],
        'api' => [
            \app\core\middlewares\AuthMiddleware::class,
        ],
    ];

    // list of default  middlewares applied to all requests
    protected array $defaultmiddlewares=[
         \app\core\middlewares\ProxyMiddleware::class,
         \app\core\middlewares\HostMiddleware::class,
    ];

    public  function Get_middleware(string $name)
    {
       return  $this->routeMiddleware[$name];
    }

    public  function Get_group_middleware(string $name)
    {
       return  $this->middlewareGroups[$name];
    }

    public  function Get_default_middleware()
    {
       return  $this->defaultmiddlewares;
    }

}