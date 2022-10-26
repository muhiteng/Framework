<?php

class m0004_refresh_token
{
    public function up()
    {
        $db=\app\core\Application::$app->db;
        $SQL="CREATE TABLE refresh_token (
            token_hash VARCHAR(64) NOT NULL,
            expires_at INT UNSIGNED NOT NULL,
            PRIMARY KEY (token_hash),
            INDEX (expires_at)
        )ENGINE=INNODB; ";

        $db->pdo->exec($SQL);

    }
    public function down()
    {
        $db=\app\core\Application::$app->db;
        $SQL="DROP TABLE refresh_token";

        $db->pdo->exec($SQL);
    }
}