<?php
namespace app\core\exception;

class AccessDenied extends \Exception
{
    protected $message ='AccessDenied ...';
    protected $code='404';

}