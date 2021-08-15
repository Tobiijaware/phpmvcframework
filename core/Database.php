<?php


namespace app\core;
//phpdotenv to read from the env file

//use mysqli;

class Database
{
    public \PDO $pdo;
    //private $obj;

    public function __construct(array $config)
    {
        try {

            $dsn = $config['dsn'] ?? '';
            $user = $config['user'] ?? '';
            $password = $config['password'] ?? '';

            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch (\PDOException $e){
            print_r($e->getMessage());
        }


    }

    public function applyMigration(){
        $this->createMigrationTable();
        $applied = $this->getAppliedMigration();
        $newMigrations = [];

        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $applied);
        foreach($toApplyMigrations as $migration){
            if($migration === '.' || $migration === '..'){continue;}

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className;
            echo "Applying migration $migration".PHP_EOL;
            $instance->up();
            echo "Applied migration $migration".PHP_EOL;
            $newMigrations[] = $migration;
        }
        
        if(!empty($newMigrations)){
            $this->saveMigration($newMigrations);
        }else{
            echo "All migrations are applied".PHP_EOL;
        }

    }

    public function createMigrationTable(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations 
                                    (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255),
                                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ) ENGINE=INNODB; ");
    }

    public function getAppliedMigration()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function saveMigration(array $newMigrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')", $newMigrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES (
            $str
        )");
        $statement->execute();
    }

}