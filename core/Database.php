<?php
namespace app\core;

class Database
{
    public   \PDO $pdo;

   // public function __construct(array $config)
    public function __construct()
    {
       
             $dsn = $_ENV['DB_DSN']??'';
            $user = $_ENV['DB_USER']??'';
            $password = $_ENV['DB_PASSWORD']??'';
            // $dsn = $config['dsn']??'';
            // $user = $config['user']??'';
            // $password = $config['password']??'';

            $this->pdo=new \PDO($dsn,$user,$password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        
       

    }
    public function applayMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $files = scandir(Application::$ROOT_DIR . '/migrations');

        /*echo '<pre>';
        print_r($files);
        echo '</pre>';*/
        //The array_diff() function compares the values of two (or more) arrays, and returns the differences.
        $toApplyMigrations = array_diff($files, $appliedMigrations);
       /* echo '<pre>';
        print_r($toApplyMigrations);
        echo '</pre>';*/
        foreach ($toApplyMigrations as $migration) {
            // . , .. refere th parents directory so continue
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("There are no migrations to apply");
        }

    }
    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
    }
    public function getAppliedMigrations()
    {
        $statement=$this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
    protected function saveMigrations(array $newMigrations)
    {
        $str = implode(',', array_map(fn($m) => "('$m')", $newMigrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES 
            $str
        ");
        $statement->execute();
    }
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    private function log($message)
    {
        echo "[" . date("Y-m-d H:i:s") . "] - " . $message . PHP_EOL;
    }
}