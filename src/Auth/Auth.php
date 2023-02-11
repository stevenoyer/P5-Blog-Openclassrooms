<?php 

namespace So\Blog\Auth;

use stdClass;
use So\Blog\Class\Model;
use So\Blog\Class\Database;
use So\Blog\Interface\AuthInterface;
use So\Blog\Controller\AuthController;

class Auth implements AuthInterface
{
    private $controller;
    private $model;

    public function init(): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE)
        {
            return session_start();
        }
    }

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->controller = new AuthController();
        $this->model = $this->controller->getModel('users');
    }

    /**
     * Login an user
     */
    public function login(array|object $user = []): bool
    {
        $_SESSION['id'] = $user->id;
        $_SESSION['is_admin'] = $user->is_admin;

        return true;
    }

    /**
     * Register an user
     */
    public function register(array|object $params = []): bool
    {
        unset($params['confirm_password']);
        $hash = $this->pwdHash($params['password']);
        $params['password'] = $hash;
        $params['is_admin'] = 0;
        $params['validate'] = 0;

        return $this->model->create($params);
    }

    /**
     * Hash the password
     */
    public function pwdHash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verif user account
     */
    public function verifyAccount(array|object $params = []): bool
    {
        $user = $this->model->find($params['id']);
        if (empty($user) || $user === false)
        {
            return false;
        }

        return true;
    }

    /**
     * Verif if user is connected
     */
    public function isConnected(): bool
    {
        if (empty($_SESSION) && empty($_SESSION['id']))
        {
            return false;
        }

        // If user does not exist
        if (!$this->verifyAccount(['id' => $_SESSION['id']]))
        {
            // Logout session and return false
            $this->logout();
            return false;
        }

        return true;
    }

    /**
     * Verif if user is admin
     */
    public function isAdmin(): bool
    {
        if ($this->model->find($_SESSION['id'])->is_admin != 1)
        {
            $_SESSION['is_admin'] = 0;
            return false;
        }

        return true;
    }

    /**
     * Logout user
     * destroy the session
     */
    public function logout(): bool
    {
        unset($_SESSION);
        session_destroy();
        session_unset();
        return true;
    }

}
