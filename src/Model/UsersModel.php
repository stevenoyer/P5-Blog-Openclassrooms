<?php 

namespace So\Blog\Model;

use Exception;
use So\Blog\Class\Model;
use stdClass;

class UsersModel extends Model
{
    protected $table = 'users';

    /**
     * Find item by email
     * @param string $email
     * @return array|object|bool
     */
    public function findByMail(string $email): array|object|bool
    {
        if (empty($email))
        {
            return throw new Exception('Please indicate the item ID');
        } 
        
        return $this->query("SELECT * FROM $this->table WHERE email = ?", [$email], true);
    }

}
