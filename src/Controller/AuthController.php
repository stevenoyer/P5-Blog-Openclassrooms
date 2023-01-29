<?php 

namespace So\Blog\Controller;

use So\Blog\Auth\Auth;
use So\Blog\Class\Controller;
use So\Blog\HTML\FormValidatorHtml;
use stdClass;

class AuthController extends Controller
{

    public function index()
    {
        $auth = new Auth();
        if ($auth->isConnected())
        {
            return $this->redirect(BASEURL . '/profil');
        }
        
        return $this->render('auth/auth.html.twig', []);
    }

    public function login()
    {
        $auth = new Auth();

        if ($auth->isConnected())
        {
            return $this->redirect(BASEURL . '/profil');
        }

        extract($_POST);

        $email = trim($email);
        $password = trim($password);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'Le format de l\'adresse e-mail est invalide.']]);
        }
        
        if (!is_string($password) || empty($password))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'Veuillez vérifier vos informations de connexion.', 'email' => $email]]);
        }

        $user = $this->getModel('users')->findByMail($email);
        if (empty($user) || $user == false)
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'L\'utilisateur ou le mot de passe est incorrect.']]);
        }

        if (!password_verify($password, $user->password))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'L\'utilisateur ou le mot de passe est incorrect.']]);
        }

        if ($auth->login($user))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        return $this->render('auth/auth.html.twig', ['error' => ['login' => 'Un problème est survenu lors de la connexion.']]);
    }

    public function register()
    {
        $auth = new Auth();
        if ($auth->isConnected())
        {
            return $this->redirect(BASEURL . '/profil');
        }

        extract($_POST);
        if (empty($name))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Veuillez vérifier votre nom et prénom.', 'name' => $name]]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Le format de l\'e-mail est incorrect.', 'email' => $email]]);
        }

        $user = $this->getModel('users')->findByMail($email);
        if (!empty($user))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Cet e-mail a déjà été utilisée.']]);
        }

        if (empty($password))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Vous devez fournir un mot de passe.']]);
        }

        if (empty($confirm_password))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Vous devez confirmer votre mot de passe.']]);
        }

        if ($password !== $confirm_password)
        {
            return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Vos mots de passes ne correspondent pas.']]);
        }

        unset($_POST['submit']);
        $validator = new FormValidatorHtml($_POST);
        $data = $validator->validate();

        $auth = new Auth();
        if ($auth->register($data))
        {
            return $this->redirect(BASEURL . '/auth');
        }
        
        return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Un problème est survenu lors de l\'inscription.']]);
    }

    public function logout()
    {
        $auth = new Auth();

        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        if ($auth->logout())
        {
            $this->redirect(BASEURL);
        }
    }
}