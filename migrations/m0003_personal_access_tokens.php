<?php

class m0003_personal_access_tokens
{
    public function up()
    {
        $db=\app\core\Application::$app->db;
        $SQL="CREATE TABLE personal_access_tokens(
            id bigint(20) AUTO_INCREMENT PRIMARY KEY,
           user_id bigint(20),
           name VARCHAR(255) DEFAULT 'my-app-token',
           token VARCHAR(255) NOT NULL,
           expire_token Datetime NOT NULL,
           abilities text DEFAULT '[*]',
           
               refresh_token VARCHAR(255) NOT NULL,
           refresh_expire_token Datetime NOT NULL,
               last_used_at TIMESTAMP Null,
           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
           updated_at TIMESTAMP null)ENGINE=INNODB; ";

        $db->pdo->exec($SQL);

    }
    public function down()
    {
        $db=\app\core\Application::$app->db;
        $SQL="DROP TABLE personal_access_tokens";

        $db->pdo->exec($SQL);
    }
}