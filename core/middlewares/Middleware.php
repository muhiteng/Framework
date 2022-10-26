<?php
namespace app\core\middlewares;
use Closure;
class Middleware
{
   /**
    * Undocumented function
    *
    * @param [type] $class
    * @param [type] $next
    * @param [type] $request
    * @return void
    */
      static function call( $class,Closure $next,$request)
      {
        return call_user_func_array([new $class,'handle'],[$next,$request]);
      }
   
}