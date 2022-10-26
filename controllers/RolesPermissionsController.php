<?php
namespace app\controllers;


use app\models\Role;
use app\core\Request;
use app\core\Response;

use app\core\Controller;
use app\models\Permission;
use app\app\resources\RoleResource;
use app\app\Requests\AddRoleRequest;
use app\core\middlewares\PermissionMiddleware;
use app\app\Interfaces\RolesPermissionsInterface;
use app\app\Interfaces\RolesPermissionsImplementaion;
use app\app\RolesPermissionsImplementaion as AppRolesPermissionsImplementaion;

class RolesPermissionsController extends Controller
{
    
    public function __construct()
    {
      // make a array of action=>permission 
      $this->registerMiddleware(new PermissionMiddleware([
      //  'profile'=>'read-role',
        
      ]));
      
    }
    
    
  
    public function getAllRoles(Request $request,Response $response)
    {
      $data=new RolesPermissionsImplementaion();
      $role=new Role();
      $role=$data->getAllRoles();
      $response->setSuccess(true);
      $response->setHttpStatusCode(200);
      $response->addMessage($role);
      $response->send();
    
    }
    public function create(Request $request,Response $response)
    {
      
      $data=$request->getBody();
      $name=$data['name'];
      $display_name=isset($data['display_name'])?$data['display_name']:'';
      $description=isset($data['description'])?$data['description']:'';

      $o=Role::create([
        'name'=>$name,
        'display_name'=>$display_name,
      'description'=>$description]);
     
      $response->setSuccess(true);
      $response->setHttpStatusCode(200);
      $response->addMessage($o);
      
      $response->send();
    

    }
   
    public function update(Request $request,Response $response)
    {
      $data=$request->getBody();
      $name=$data['name'];
      $display_name=isset($data['display_name'])?$data['display_name']:'';
      $description=isset($data['description'])?$data['description']:'';

     $o=Role::update(15,['name'=>'role_update','display_name'=>'2','description'=>'2']);
      $response->setSuccess(true);
      $response->setHttpStatusCode(200);
      $response->addMessage($o);
      
      $response->send();
    
    }
   
}