<?php
namespace app\core;

class Router
{
    protected array $routes=[];
    public Request $request ;

    public function __construct(Request $request)
    {
        $this->request=$request;
        
    }
    public function get($path,$callback)
    {
        $this->routes['get'][$path]=$callback;
    }
    public function resolve()
    {
        $path=$this->request->getPath();
        $method=$this->request->getmethod();

        $callback=$this->routes[$method][$path]?? false;
       
        if($callback === false)
        {
            echo 'Not found';
            exit;
        }
        if(is_string($callback))
        {
            $this->renderView($callback);
        }
       /*  call_user_func($callback);
        echo '<pre>';
         var_dump($callback);
         echo '</pre>';
         echo '<pre>';
         var_dump($_SERVER);
         echo '</pre>';
         */
    }
    public function renderView($view)
    {
        require_once __DIR__."/../views/{$view}.php";

    }
}