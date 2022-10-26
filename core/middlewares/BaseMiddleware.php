<?php
namespace app\core\middlewares;

use app\core\Request;
use Closure;

abstract class BaseMiddleware
{
    abstract public function handle(Closure $next,Request $request);
   
}