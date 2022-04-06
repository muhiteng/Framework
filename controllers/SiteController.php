<?php
namespace app\controllers;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
class SiteController extends Controller
{
    public   function home()
    {
        $params=[
            'name'=>'the codic'
        ];

        // render function found in core/Controller clas which extends this clas
        return $this->render('home',$params);
        
    }
    public   function Contact()
    {

        return Application::$app->router->renderView('contact');
        return 'Contact  data';
    }
    public   function handleContact(Request $request)
    {
       /* echo '<pre>';
        var_dump($_POST);
        echo '</pre>';*/

        $body=$request->getBody();
       echo '<pre>';
        var_dump($body);
        echo '</pre>';/**/
        return 'handling submitted data';
    }
}