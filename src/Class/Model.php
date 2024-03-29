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
            new Exception('Vous n\'avez pas défini de table dans le contructeur du model.');
        }

        $this->table = rtrim(strtolower($this->table));
        $this->db = new Database();
    }

    /**
     * Read data
     */
    public function read(int $limit = 5): array|object|bool
    {
        $limit_sql = '';

        if ($limit >= 1)
        {
            $limit_sql = "LIMIT $limit";
        }

        return $this->db->query("SELECT * FROM $this->table ORDER BY id DESC $limit_sql");
    } 

    /**
     * Create an item
     */
    public function create(array $fields = []): array|object|bool
    {
        if (empty($fields))
        {
            return throw new Exception('No values have been sent.');
        }

        $sql_parts = [];
        $attributes = [];
        $sql = '';

        /**
         * $key = column name
         * $value = value in column name
         */
        foreach ($fields as $key => $value)
        {
            $sql_parts[] = "$key = ?";
            $attributes[] = $value;
        }

        $sql = implode(', ', $sql_parts);
        return $this->query("INSERT INTO $this->table SET $sql", $attributes, true);
    }

    /**
     * Update an item
     */
    public function update(int $id, array $fields = []): array|object|bool
    {
        if (empty($id))
        {
            return throw new Exception('Please indicate the item ID');
        }

        if (empty($fields))
        {
            return throw new Exception('Please indicate the values');
        }

        $sql_parts = [];
        $attributes = [];
        $sql = '';

        /**
         * $key = column name
         * $value = value in column name
         */
        foreach ($fields as $key => $value)
        {
            $sql_parts[] = "$key = ?";
            $attributes[] = $value;
        }

        $attributes[] = $id;
        $sql = implode(', ', $sql_parts);
        return $this->query("UPDATE $this->table SET $sql WHERE id = ?", $attributes, true);
    }

    /**
     * Delete an item
     */
    public function delete(int $id): bool
    {
        if (empty($id))
        {
            return throw new Exception('Please indicate the item ID');
        } 

        return $this->query("DELETE FROM $this->table WHERE id = ?", [$id], true);
    }

    /**
     * Find item by id
     */
    public function find(mixed $search, string $type = 'id', bool $one = true): array|object|bool
    {
        if (empty($search))
        {
            return throw new Exception('Please indicate the item search');
        } 
        
        return $this->query("SELECT * FROM $this->table WHERE $type = ?", [$search], $one);
    }

    /**
     * Custom query
     */
    public function query(string $statement, array $values = null, bool $one = false): array|object|bool
    {
        if (!empty($values))
        {
            return $this->db->prepare($statement, $values, $one);
        }

        return $this->db->query($statement, $one);
    }
    
    /**
     * Get the last insert id in DB
     */
    public function lastId(): string|false
    {
        return $this->db->getLastInsertId();
    }
}
