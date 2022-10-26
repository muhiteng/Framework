<?php 
namespace app\models;


 class Permission extends Model 
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
        return 'permissions';
    }
    public function className():string
    {
        return 'app\\models\\Permission';
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
     public function rules() :array
    {
        return[
            'name'=>[self::RULE_REQUIRED],
            

        ];
    }
    public function attributes():array{
        return ['name','display_name','description'];
    }
    
}