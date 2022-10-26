<?php
namespace app\core;

use app\app\Requests\BaseRequest;

class Request 
{
    public function getPath()
    {
        $path=$_SERVER['REQUEST_URI'] ??'/';
        $position=strpos($path,'?');
         if($position === false){
             return $path;
         }
       return  $path=substr($path,0,$position);
       /*  echo '<pre>';
         var_dump($position);
         echo '</pre>';
         */

    }
    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']) ;
    }
    public function isGet()
    {
        return $this->method()==='get' ;
    }
    public function isPost()
    {
        return $this->method()==='post' ;
    }
    public function getBody()
    {
        $body=[];
        if($this->method() ==='get')
        {
            foreach($_GET as $key=>$value)
            {
                //filter_input — Gets a specific external variable by name and optionally filters it
                $body[$key]=filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->method() ==='post')
        {
            foreach($_POST as $key=>$value)
            {
                //filter_input — Gets a specific external variable by name and optionally filters it
                $body[$key]=filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body ;
    }
}