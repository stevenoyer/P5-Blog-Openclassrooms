<?php 

namespace So\Blog\Class;

use Config;
use PDO;

class Database
{
    private string $host;
    private string $db;
    private string $user;
    private string $password;
    private $pdo;
    private $config;

    public function __construct(Config $config)
    {
        // Get config
        $this->config = $config;

        // Set variables
        $this->host = $config->host;
        $this->db = $config->db;
        $this->user = $config->user;
        $this->password = $config->password;
    }

    /**
     * Method get PDO for instanciate PDO
     */
    private function getDB(): \PDO
    {
        if (is_null($this->pdo))
        {
            $pdo = new PDO("mysql:dbname=$this->db;host=$this->host;charset=utf8mb4", $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }

        return $this->pdo;
    }

    /**
     * Query to database
     */
    public function query(string $statement, bool $one = false): array|object|bool
    {
        $req = $this->getDB()->query($statement);

        if (
            strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) 
        {
            return $req;
        }

        $req->setFetchMode(PDO::FETCH_OBJ);

        if ($one) 
        {
            $data = $req->fetch();
        }
        else 
        {
            $data = $req->fetchAll();
        }

        return $data;
    }

    /**
     * Prepare to database
     */
    public function prepare(string $statement, array $values, bool $one = false): array|object|bool
    {
        $req = $this->getDB()->prepare($statement);
        $res = $req->execute($values);

        if (
            strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            return $res;
        }

        $req->setFetchMode(PDO::FETCH_OBJ);

        if ($one) 
        {
            $data = $req->fetch();
        }
        else 
        {
            $data = $req->fetchAll();
        }

        return $data;
    }
    
}
