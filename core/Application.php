<?php
namespace app\core;

use app\core\Router;

class Application
{
    public Router $router ;
    public Request $request ;
    public $s='jj';
    public function __construct()
    {
        $this->request=new Request();
        $this->router=new Router($this->request);
        
    }
    public function run()
    {
       return $this->router->resolve();
    }
}