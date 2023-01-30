<?php 

namespace So\Blog\Security;

class CsrfToken
{
    private $salt = 'T3BlbkNsYXNzcm9vbXMgQmxvZyBQNSBTdGV2ZW4gT3llcg==';

    /**
     * Generate a csrf token
     */
    public function generate(): string
    {
        return sha1($this->salt . session_id() . $this->salt);
    }

    /**
     * Verify if token is valid
     */
    public function verif(string $token): bool
    {
        $verif = sha1($this->salt . session_id() . $this->salt);

        if ($token === $verif)
        {
            return true;
        }

        return false;
    }

}
