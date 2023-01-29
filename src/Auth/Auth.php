<?php 

namespace So\Blog\Auth;

use So\Blog\Class\Database;
use So\Blog\Class\Model;
use So\Blog\Controller\AuthController;
use So\Blog\Interface\Auth as InterfaceAuth;
use stdClass;

class Auth implements InterfaceAuth
{
    private $controller;
    private $model;

    public function init()
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
        $hash = password_hash($params['password'], PASSWORD_BCRYPT);
        $params['password'] = $hash;
        $params['is_admin'] = 0;
        $params['validate'] = 0;

        return $this->model->create($params);
    }

    /**
     * Verif user account
     */
    public function verifyAccount(array|object $params = []): bool
    {
        $user = $this->model->findByMail($params['email']);
        if (empty($user) || $user == false)
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

        return true;
    }

    /**
     * Verif if user is admin
     */
    public function isAdmin(): bool
    {
        if ($_SESSION['is_admin'] != 1)
        {
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