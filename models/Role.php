<?php 
namespace app\models;


 class Role extends Model 
{
   

    public string $name='';
    public string $display_name='';
    public string $description='';
   
    
    //  public function tableName():string
    // {
    //     return 'roles';
    // }
    public   static function tableName()
     {
        return 'roles';
    }
    public function className():string
    {
        return 'app\\models\\Role';
    }
    public function primaryKey():string
    {
        return 'id';
    }
    public function getDisplayName():string
    {
        
        return $this->display_name;
    }
    /*public static  function create(array $values)
    {
        
        return parent::create(self::tableName(),$values,self::className());
    }*/
     
    public function attributes():array{
        return ['name','display_name','description'];
    }
    
}