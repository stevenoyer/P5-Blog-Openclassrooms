<?php 

namespace So\Blog\Class;

use Exception;

class Model
{
    protected $table;
    protected $db;

    public function __construct()
    {
        if (is_null($this->table))
        {
            return new Exception('Vous n\'avez pas défini de table dans le contructeur du model.');
        }

        $this->table = rtrim(strtolower($this->table));
    }

    public function read()
    {
        // Return query("SELECT * FROM $this->table");
    } 

    public function create(array $fields = []): void
    {
        // empty($fields) return throw new Exception
        /**
         * Else
         * 
         * foreach ($fields as $key => $value) => 
         *                                      $key = column name
         *                                      $value = value in column name
         *      $sql_parts[] = "$key = ?",
         *      $attributes[] = $value;
         * 
         * $sql = implode(', ', $sql_parts);
         * prepare("INSERT INTO $this->table SET $sql", $attributes, true);
         */
    }

    public function update(int $id, array $fields = []): void
    {
        // empty($fields) return throw new Exception
        // empty($id) return throw new Exception
        /**
         * Else
         * 
         * foreach ($fields as $key => $value) => 
         *                                      $key = column name
         *                                      $value = value in column name
         *      $sql_parts[] = "$key = ?",
         *      $attributes[] = $value;
         * 
         * $sql = implode(', ', $sql_parts);
         * prepare("UPDATE $this->table SET $sql WHERE id = ?", $attributes, true);
         */
    }

    public function delete(int $id): void
    {
        // empty($id) return throw new Exception
        // Return query("DELETE FROM $this->table WHERE id = ?", [$id], true);
    }

    public function find(int $id): void
    {
        // empty($id) return throw new Exception
        // Return query("SELECT * FROM $this->table WHERE id = ?", [$id], true);
    }

    public function query(string $statement, array $values = null, bool $one = false): void
    {
        // Custom query
        /**
         * if (!empty($values)):
         *      return prepare($statement, $values, $one)
         * else:
         *      return query($statement, $one)
         */
    }
}