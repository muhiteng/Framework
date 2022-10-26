<?php 
namespace app\models;

use app\core\Database;

abstract class Model{
  

   // abstract public  function tableName(): string;
    abstract public  function className():string;
    abstract public  function primaryKey():string;
    public   static function tableName(){}
    
    public static function getTableName() {
       return static::tableName(); 
    }
   
    public static  function create($values)
    { 
        $tableName = self::getTableName();
        
        $sql="insert into {$tableName} ";
        
            $sql  = "INSERT INTO {$tableName}";
            $sql .= " (`".implode("`, `", array_keys($values))."`,`created_at`)";
            $sql .= " VALUES ('".implode("', '", $values)."',NOW())";
        
            
            $database=new Database();
            $stmt = $database->pdo->prepare($sql);
            $stmt->execute();
            $id = $database->pdo->lastInsertId();
            
        
        return $id;
    }
    public static  function update($id,$values)
    { 
        $tableName = self::getTableName();
        $primaryKey='id';
        foreach ($values as $column => $value) {
            $conditions[] = "`{$column}` = '{$value}'";
        }
        $conditions[] = "`updated_at` = NOW()";
    
        $conditions = implode(',', $conditions);
    
        $sql="UPDATE ". $tableName." SET {$conditions} WHERE {$primaryKey} = {$id}";
            //return $sql;

            $database=new Database();
            $stmt = $database->pdo->prepare($sql);
            $stmt->execute();
            $id = $database->pdo->lastInsertId();
            
        
        return $id;
    }
    

}