<?php
namespace app\core;

use app\core\Router;
use app\core\Database;
use \Exception;
class Application
{
    
    public static string $ROOT_DIR;
    public Router $router ;
    public Request $request ;
    public Response $response ;
    
    public Database $db;
    public static Application $app;
    public  ?Controller $controller=null;
   
    public function __construct($rootpth)
    {
        self::$ROOT_DIR = $rootpth;
        self::$app = $this;
        $this->request=new Request();
        $this->response=new Response();
       
        $this->db=new Database();
        $this->router=new Router($this->request,$this->response);  
    }
  
    public function run()
        {
          
               try
                {
                    echo $this->router->resolve();
                }
                catch(Exception $e)
                {
                    $this->response->setSuccess(false);
                    $this->response->setHttpStatusCode(200);
                    $this->response->addMessage('Wrong ...');
                    $this->response->send();
                    exit;
                }
            
       
    }
    public function getController():Controller
    {
       return $this->controller;
    }
    public function setController(Controller $controller):void
    {
        $this->controller=$controller;
    }
}