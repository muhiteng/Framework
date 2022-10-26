<?php

namespace app\core;
use app\core\Model;
abstract  class DbModel extends Model
{
    abstract public  function tableName(): string;
    abstract public  function className(): string;
    abstract public  function attributes(): array;
    abstract public  function primaryKey(): string;
   

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(",", $attributes) . ") 
                VALUES (" . implode(",", $params) . ")");

               /* echo '<pre>';
                var_dump($statement,$params,$attributes);
                echo '</pre>';*/
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }

    public static function prepare($sql): \PDOStatement
    {
        return Application::$app->db->prepare($sql);
    }

    //public static function findOne($where)
    public  function findOne($where)
    {
        
       // $tableName = $this->tableName();
        $tableName = 'users';
        
        $attributes = array_keys($where);
        // here ($attr) => "$attr = :$attr" means
        // email  => email=:email
        // implode combain AND  betweet it
        $sql = implode("AND", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject($this->className());
      // return $statement->fetchObject(static::class);
        // return $statement->fetchObject('app\\models\\User');
    }
}