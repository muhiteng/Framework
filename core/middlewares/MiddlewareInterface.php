<?php
namespace app\core\middlewares;
use app\core\Request;
use Closure;

interface MiddlewareInterface
{
    function handle(Closure $next,Request $request);
}