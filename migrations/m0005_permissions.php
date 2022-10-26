<?php

class m0005_permissions
{
    public function up()
    {
        // create roles table
        $db=\app\core\Application::$app->db;
        $SQL="CREATE TABLE  IF NOT EXISTS roles (
             id INT AUTO_INCREMENT PRIMARY KEY,
             name VARCHAR(64) NOT NULL,
             display_name VARCHAR(64) NOT NULL,
             description VARCHAR(64) NOT NULL,
             created_at DATETIME  NOT NULL,
            updated_at DATETIME  NULL,
            deleted_at DATETIME  NULL
        )ENGINE=INNODB; ";

        $db->pdo->exec($SQL);

        // create permissions table
        $SQL="CREATE TABLE  IF NOT EXISTS permissions (
             id INT AUTO_INCREMENT PRIMARY KEY,
             name VARCHAR(64) NOT NULL,
             display_name VARCHAR(64) NOT NULL,
             description VARCHAR(64) NOT NULL,
             created_at DATETIME  NOT NULL,
            updated_at DATETIME  NULL,
            deleted_at DATETIME  NULL
        )ENGINE=INNODB; ";

        $db->pdo->exec($SQL);

        // create role_user table
        $SQL="CREATE TABLE  IF NOT EXISTS role_user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            role_id INT(6)  NOT NULL,
            user_id INT(6)  NOT NULL,
            user_type VARCHAR(64) default 'app\models\User',
            FOREIGN KEY (role_id) REFERENCES roles(id),
            FOREIGN KEY (user_id) REFERENCES users(id)
            
       )ENGINE=INNODB; ";
       $db->pdo->exec($SQL);

       // create permission_user table
       $SQL="CREATE TABLE  IF NOT EXISTS permission_user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        permission_id INT(6)  NOT NULL,
        user_id INT(6)  NOT NULL,
        user_type VARCHAR(64) default 'app\models\User',
        FOREIGN KEY (permission_id) REFERENCES permissions(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
   )ENGINE=INNODB; ";
   $db->pdo->exec($SQL);

   // create permission_role table
   $SQL="CREATE TABLE  IF NOT EXISTS permission_role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permission_id INT(6)  NOT NULL,
    role_id INT(6)  NOT NULL,
    FOREIGN KEY (permission_id) REFERENCES permissions(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
)ENGINE=INNODB; ";
$db->pdo->exec($SQL);
       

    }
    public function down()
    {
        $db=\app\core\Application::$app->db;
        $SQL="DROP TABLE roles";

        $db->pdo->exec($SQL);
        $SQL="DROP TABLE permissions";

        $db->pdo->exec($SQL);
        $SQL="DROP TABLE role_user";

        $db->pdo->exec($SQL);
    }
}