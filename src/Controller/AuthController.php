<?php 

namespace So\Blog\Controller;

use So\Blog\Auth\Auth;
use So\Blog\Class\Controller;
use So\Blog\Helper\MailHelper;
use So\Blog\HTML\FormValidatorHtml;
use stdClass;

class AuthController extends Controller
{
    /**
     * Get Auth class
     */
    private function getAuth(): object
    {
        return new Auth();
    }

    /**
     * Show authentication page
     */
    public function index(): string|bool
    {
        if ($this->getAuth()->isConnected())
        {
            $this->redirect(BASEURL . '/profil');
            return false;
        }
        
        return $this->render('auth/auth.html.twig', []);
    }

    /**
     * Proccess login auth
     */
    public function login(): string|bool
    {
        if ($this->getAuth()->isConnected())
        {
            $this->redirect(BASEURL . '/auth');
            return false;
        }

        extract($_POST);

        $email = trim($email);
        $password = trim($password);

        if (empty($token))
        {
            $this->redirect(BASEURL . '/auth');
            return false;
        }

        if (!$this->csrf->verif($token))
        {
            $this->redirect(BASEURL . '/auth');
            return false;
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

        if ($user->validate != 1)
        {
            return $this->render('auth/auth.html.twig', ['error' => ['login' => 'L\'utilisateur n\'a pas été validé. Veuillez confirmer votre compte avec l\'e-mail qui vous a été envoyé lors de votre inscription.']]);
        }

        if ($this->getAuth()->login($user))
        {
            $this->redirect(BASEURL . '/profil');
            return true;
        }

        return $this->render('auth/auth.html.twig', ['error' => ['login' => 'Un problème est survenu lors de la connexion.']]);
    }

    /**
     * Process register auth
     */
    public function register(): string|bool
    {
        if ($this->getAuth()->isConnected())
        {
            $this->redirect(BASEURL . '/profil');
            return false;
        }

        extract($_POST);

        if (empty($token))
        {
            $this->redirect(BASEURL . '/auth');
            return false;
        }

        if (!$this->csrf->verif($token))
        {
            $this->redirect(BASEURL . '/auth');
            return false;
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

        $validator = new FormValidatorHtml($_POST);
        $data = $validator->validate();

        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'token_validation' => sha1(time()),
            'token_expiration' => time() + 7200
        ];

        if ($this->getAuth()->register($user))
        {
            $helper = new MailHelper;
            if ($helper->sendMailConfirmation($user))
            {
                return $this->render('auth/auth.html.twig', ['success' => ['register' => 'Votre compte a bien été créé, un e-mail de confirmation vous a été envoyé : ' . $data['email'] . '.']]);
            }

            return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Un problème est survenue lors de l\'envoi du mail de confirmation à l\'adresse suivante : ' . $data['email'] . '.']]);
        }
        
        return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Un problème est survenu lors de l\'inscription.']]);
    }

    /**
     * Logout the user
     */
    public function logout(): bool
    {
        if (!$this->getAuth()->isConnected())
        {
            $this->redirect(BASEURL . '/auth');
            return true;
        }

        if ($this->getAuth()->logout())
        {
            $this->redirect(BASEURL . '/');
            return true;
        }
    }

    /**
     * This function confirm user account
     */
    public function confirm(string $token): string
    {
        $user = $this->getModel('users')->find($token, 'token_validation');
        if (time() > $user->token_expiration)
        {
            return $this->render('auth/auth.html.twig', ['error' => ['register' => 'Le token a expiré, veuillez contacter un administrateur pour qu\'il valide votre compte.']]);
        }

        $this->getModel('users')->update($user->id, ['validate' => 1, 'token_validation' => null, 'token_expiration' => null]);
        return $this->render('auth/auth.html.twig', ['success' => ['login' => 'Votre compte a bien validé, veuillez vous connecter.']]);
    }

}
