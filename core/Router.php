<?php
namespace app\core;
use app\core\Kernal;
use app\core\Application;
use app\core\exception\NotFoundException;
use app\core\middlewares\Middleware;
use app\models\User;
use Closure;

class Router
{
    protected array $routes=[];
    protected array $middlware=[];
    protected array $roles=[];
    public Request $request ;
   // protected array $request_data=[];
    public Response $response ;
    public Kernal $k;
    public Closure $next;
    public string $path='/';
    public function __construct(Request $request,Response $response)
    {
        $this->middlware=array();
        
        $this->request=$request;
       // $this->request_data=$request->getBody();
        $this->response=$response;
        
    }
    public function get($path,$callback)
    {
       
        $this->routes['get'][$path]=$callback;
        $this->path=$path;
        
        return $this;
    }
    public function middlware(string $middleware)
    {
        
        $this->middlware[$this->path][]=$middleware;
        
        // If you want to call multiple functions on one object in one line, 
        //you have to return $this in your functions
        return $this;
    }
    public function rolemiddlware(string $middleware)
    {
        
        $position=strpos($middleware,':');
         $allowed_roles=substr($middleware,$position+1);
         $roles=explode("|",$allowed_roles);
 
        $this->roles[$this->path]=$roles;
        // echo json_encode(["message" => $this->roles[$this->path]]);
        //  exit; 
        // If you want to call multiple functions on one object in one line, 
        //you have to return $this in your functions
        return $this;
    }
    public function post($path,$callback)
    {
        $this->routes['post'][$path]=$callback;
        $this->path=$path;
        return $this;
    }
    public function resolve()
    {
        $path=$this->request->getPath();
        $method=$this->request->method();

        $callback=$this->routes[$method][$path]?? false;
       
        if($callback === false)
        {
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(200);
            $this->response->addMessage('Not found ...');
            $this->response->send();
            exit;
        }
        if(is_string($callback))
        {
            
            $this->response->setSuccess(false);
            $this->response->setHttpStatusCode(200);
            $this->response->addMessage('Wrong ...');
            $this->response->send();
            exit;
        }
       
       
        if(is_array($callback))
        {
         // to convert array to object to work right with call_user_func   
           
            $controller=new $callback[0]();
            Application::$app->controller=$controller;
            Application::$app->controller->action= $callback[1];
            $callback[0]=$controller;
    
        
            // example auth , proxy
            if(isset($this->middlware[$path]))
            foreach($this->middlware[$path] as $middleware)
            {
                $this->k=new Kernal();

                $mid_group=$this->k->Get_group_middleware($middleware);
             
                $next = function ($parameter) {
                    return $parameter ;
                };

                
                //app\core\middlewares\HostMiddleware
                //app\core\middlewares\ProxyMiddleware
                foreach( $mid_group as $middleware)
                {
                    
                    $this->request=Middleware::call(new $middleware,$next,$this->request);
                
                }
            

            }
        }

        //check if action have permission to execute by the user
        $this->middleWarePermissions($controller);
       
         
        //Auth::$userId;
        // get all user roles
        $has_role = false;
        if(isset(Auth::$userId))
        {
           $auth_user_roles=User::roles_by_user_id(Auth::$userId);
            // echo json_encode(["message" => $this->roles]);
             
         
              foreach($auth_user_roles as $auth_user_role)
              {
                  
                 if(in_array($auth_user_role['name'], $this->roles[$path])) 
                  {
                        $has_role=true;
                  }
                if($has_role)
                 break;
              }
           
        
        }
       
       if(isset($this->roles[$path]))
       {
        if(count($this->roles)>0)
        {
         if($has_role)
           {
               // execute array($classname, $functionname)
               return call_user_func($callback,$this->request,$this->response);
           }
               else
               {
                   echo json_encode(["message" => 'have no access']);
                   exit; 
               }   
        }
        else
        {
           return call_user_func($callback,$this->request,$this->response);
        }
         
       }
       else{
        return call_user_func($callback,$this->request,$this->response);
       }
       
         
    }

   //check if action have permission to execute by the user
    public function middleWarePermissions(Controller $controller)
    {
        foreach($controller->getMiddlewares() as $middleware)
        {
            $middleware->execute();
        } 
    }
 
       
        
         
      
}