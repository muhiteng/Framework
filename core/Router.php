<?php
namespace app\core;
use app\core\Application;

class Router
{
    protected array $routes=[];
    public Request $request ;
    public Response $response ;

    public function __construct(Request $request,Response $response)
    {
        $this->request=$request;
        $this->response=$response;
        
    }
    public function get($path,$callback)
    {
        $this->routes['get'][$path]=$callback;
    }
    public function post($path,$callback)
    {
        $this->routes['post'][$path]=$callback;
    }
    public function resolve()
    {
        $path=$this->request->getPath();
        $method=$this->request->method();

        $callback=$this->routes[$method][$path]?? false;
       
        if($callback === false)
        {
            $this->response->setStatusCode(404);
           // Application::$app->response->setStatusCode(404);
            return $this->renderContent('_404');;
            exit;
        }
        if(is_string($callback))
        {
            
          return  $this->renderView($callback);
        }
        /*
        echo '<pre>';
        var_dump($callback);
        echo '</pre>';
        */
       
        if(is_array($callback))
        {
         // to convert array to object to work right with call_user_func   
            //$callback[0]=new $callback[0]();
            Application::$app->controller=new $callback[0]();
            $callback[0]=Application::$app->controller;
        }
         // execute array($classname, $functionname)
         return call_user_func($callback,$this->request);
       /* echo '<pre>';
         var_dump($callback);
         echo '</pre>';
         echo '<pre>';
         var_dump($_SERVER);
         echo '</pre>';
         */
    }
    public function renderContent($view)
    {
        $layoutcontent=$this->layoutcontent();
        $viewcontent=$this->rendeOnlyView($view);
       //echo $layoutcontent;
       //echo $viewcontent;
        return str_replace('{{content}}',$viewcontent,$layoutcontent);
        //include_once Application::$ROOT_DIR."/views/{$view}.php";
       // require_once __DIR__."/../views/{$view}.php";

    }
    public function renderView($view,$params=[])
    {
        $layoutcontent=$this->layoutcontent();
        $viewcontent=$this->rendeOnlyView($view,$params);
       //echo $layoutcontent;
       //echo $viewcontent;
        return str_replace('{{content}}',$viewcontent,$layoutcontent);
        //include_once Application::$ROOT_DIR."/views/{$view}.php";
       // require_once __DIR__."/../views/{$view}.php";

    }
    protected function layoutcontent()
    { 
        $layout=Application::$app->controller->layout;
        //a built-in function of PHP to enable the output buffering. 
        //If the output buffering is enabled, then all output will be
        // stored in the internal buffer and no output from the script will be sent to the browser.
        // Some other built-in functions are used with ob_start() function.
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/{$layout}.php";
        //ob_get_clean is an in-built PHP function that is used to clean or delete the current output buffer. 
        //It's also used to get the output buffering again after cleaning the buffer.
        // The ob_get_clean() function is the combination of both ob_get_contents() and ob_end_clean().
        return ob_get_clean();

    }
    protected function rendeOnlyView($view,$params)
    { 
       /* echo '<pre>';
        print_r($params);
        echo '</pre>';
     */
    foreach($params as $key=>$value)
    {
        // create variable its name is the same of the key
        $$key=$value;
    }
        ob_start();
         
        include_once Application::$ROOT_DIR."/views/{$view}.php";
       
        return ob_get_clean();

    }
}