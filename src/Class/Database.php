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

    public function __construct()
    {
        // Get config
        $config = new Config();

        // Set variables
        $this->host = $config->host;
        $this->db = $config->db;
        $this->user = $config->user;
        $this->password = $config->password;
    }

    /**
     * @return \PDO instance of pdo
     */
    private function getDB()
    {
        if (is_null($this->pdo))
        {
            $pdo = new PDO("mysql:dbname=$this->db;host=$this->host;charset=utf8", $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }

        return $this->pdo;
    }

    /**
     * @param string $statement
     * @param bool $one
     * 
     * @return Object|Array
     */
    public function query($statement, $one = false)
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
     * @param string $statement
     * @param array $values
     * @param bool $one
     * 
     * @return Object|Array
     */
    public function prepare($statement, $values, $one = false)
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
