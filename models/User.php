<?php 
namespace app\models;

use app\core\Database;
use PDO;
use app\core\DbModel;
use app\core\UserModel;

class User extends UserModel
{
    const STATUS_INACTIVE=0;
    const STATUS_ACTIVE=1;
    const STATUS_DELETED=2;

    public string $firstname='';
    public string $lastname='';
    public string $email='';
    public int $status = self::STATUS_INACTIVE;
    public string $password='';
    public string $confirmPassword='';
    
     public function tableName():string
    {
        return 'users';
    }
    public function className():string
    {
        return 'app\\models\\User';
    }
    public function primaryKey():string
    {
        
        return 'id';
    }
    public function getDisplayName():string
    {
        
        return $this->firstname.' '.$this->lastname;
    }
    public function save()
    {
        $this->status=self::STATUS_INACTIVE;
        $this->password=password_hash($this->password,PASSWORD_DEFAULT);
        return parent::save();
    }
     public function rules() :array
    {
        return[
            'firstname'=>[self::RULE_REQUIRED],
            'lastname'=>[self::RULE_REQUIRED],
            'email'=>[self::RULE_REQUIRED,self::RULE_EMAIL,[self::RULE_UNIQUE,'class'=>self::class]],
            'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MAX,'max'=>24]],
            'confirmPassword'=>[self::RULE_REQUIRED,[self::RULE_MATCH,'match'=>'password']],

        ];
    }
    public function attributes():array{
        return ['firstname','lastname','email','password','status'];
    }
    public function labels():array
    {
        return [
            'firstname'=>'First name',
            'lastname' =>'Last name',
            'email'=>'Email',
            'password'=>'Password',
            'confirmPassword'=>'Confirm Password',
            'status'=>'Status'];
    }
    public function permissions()
    {
        $sql = "SELECT permissions.name
        from permissions
         INNER JOIN permission_role on permissions.id = permission_role.permission_id
         INNER JOIN roles on roles.id = permission_role.role_id
         INNER JOIN role_user on roles.id = role_user.role_id
          where role_user.user_id=:id";
        $database=new Database();
        $stmt = $database->pdo->prepare($sql);
        
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        
        $stmt->execute();
      
        $data = [];
        $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // display the permission name
        foreach ($permissions as $permission) {
            $data[] = $permission['name'];
        }
   

        return $data;
    }
    public function roles()
    {
        $sql = "SELECT roles.name
        from roles
        INNER JOIN role_user on roles.id = role_user.role_id
        where role_user.user_id=:id";
        $database=new Database();
        $stmt = $database->pdo->prepare($sql);
        
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        
        $stmt->execute();
      
        $data = [];
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // display the permission name
        foreach ($roles as $role) {
            $data[] = $role['name'];
        }

        return $data;
    }
    public static function roles_by_user_id($id)
    {
        $sql = "SELECT roles.name
        from roles
        INNER JOIN role_user on roles.id = role_user.role_id
        where role_user.user_id=:id";
        $database=new Database();
        $stmt = $database->pdo->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);
        
        $stmt->execute();
      
        $data = [];
                
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

}