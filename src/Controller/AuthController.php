<?php 

namespace So\Blog\Controller;

use So\Blog\Auth\Auth;
use So\Blog\Class\Controller;
use So\Blog\HTML\FormValidatorHtml;
use stdClass;

class AuthController extends Controller
{
    private function getAuth(): object
    {
        return new Auth();
    }

    public function index(): string
    {
        if ($this->getAuth()->isConnected())
        {
            return $this->redirect(BASEURL . '/profil');
        }
        
        return $this->render('auth/auth.html.twig', []);
    }

    public function login(): string
    {
        if ($this->getAuth()->isConnected())
        {
            return $this->redirect(BASEURL . '/profil');
        }

        extract($_POST);

        $email = trim($email);
        $password = trim($password);

        if (empty($token))
        {
            return $this->redirect(BASEURL . '/auth');
        }

        if (!$this->csrf->verif($token))
        {
            return $this->redirect(BASEURL . '/auth');
        }

        unset($_POST['token']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'Le format de l\'adresse e-mail est invalide.']]);
        }
        
        if (!is_string($password) || empty($password))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'Veuillez vérifier vos informations de connexion.', 'email' => $email]]);
        }

        $user = $this->getModel('users')->findByMail($email);
        if (empty($user) || $user === false)
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'L\'utilisateur ou le mot de passe est incorrect.']]);
        }

        if (!password_verify($password, $user->password))
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'L\'utilisateur ou le mot de passe est incorrect.']]);
        }

        if ($this->getAuth()->login($user))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        return $this->render('auth/auth.html.twig', ['error' => ['login' => 'Un problème est survenu lors de la connexion.']]);
    }

    public function register(): string
    {
        if ($this->getAuth()->isConnected())
        {
            return $this->redirect(BASEURL . '/profil');
        }

        extract($_POST);

        if (empty($token))
        {
            return $this->redirect(BASEURL . '/auth');
        }

        if (!$this->csrf->verif($token))
        {
            return $this->redirect(BASEURL . '/auth');
        }

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

        unset($_POST['token']);
        unset($_POST['submit']);
        $validator = new FormValidatorHtml($_POST);
        $data = $validator->validate();

        if ($this->getAuth()->register($data))
        {
            return $this->redirect(BASEURL . '/auth');
        }
        
        return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Un problème est survenu lors de l\'inscription.']]);
    }

    public function logout(): string
    {
        if (!$this->getAuth()->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        if ($this->getAuth()->logout())
        {
            $this->redirect(BASEURL);
        }
    }

}
