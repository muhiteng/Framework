<?php
namespace app\app\Interfaces;

use PDO;
use app\models\Role;
use app\core\Database;

class RolesPermissionsImplementaion implements RolesPermissionsInterface
{
    // get all roles
    public function getAllRoles()
    {
        $sql = "select name,description from roles";
        $database=new Database();
        $stmt = $database->pdo->prepare($sql);
        
      //  $stmt->bindValue(":id", $id, PDO::PARAM_STR);
        
        $stmt->execute();
      
        $role=new Role();
        $role = $stmt->fetchAll(PDO::FETCH_CLASS, "app\\models\\Role");
        
        return $role;
    }
}
