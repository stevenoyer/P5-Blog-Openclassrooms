<?php 

namespace So\Blog\Controller;

use So\Blog\Auth\Auth;
use So\Blog\Class\Controller;
use So\Blog\Class\Mail;
use So\Blog\HTML\FormValidatorHtml;

class UsersController extends Controller
{
    /**
     * View profile user page
     */
    public function profil(): string|bool
    {
        $auth = new Auth();
        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        $user = $this->getModel()->find($_SESSION['id']);
        $user_comments = $this->getModel('comments')->findCommentsByUserId($_SESSION['id']);
        return $this->render('users/profil.html.twig', ['user' => $user, 'comments' => $user_comments]);
    }

    /**
     * View profile edit user page
     */
    public function edit(): string|bool
    {
        $auth = new Auth();
        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        $user = $this->getModel()->find($_SESSION['id']);
        return $this->render('users/edit.html.twig', ['user' => $user]);
    }

    /**
     * Process update user profile
     */
    public function updateProfil(): string|bool
    {
        $auth = new Auth();
        $user = $this->getModel()->find($_SESSION['id']);

        if (empty($_POST['token']))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        if (!$this->csrf->verif($_POST['token']))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        if (empty($_POST['password']) && empty($_POST['confirm_password']))
        {
            unset($_POST['password']);
            unset($_POST['confirm_password']);
        }

        if (empty($_POST['name']))
        {
            return $this->render('users/edit.html.twig', ['user' => $user, 'error' => 'Merci de bien vouloir entrer un nom.']);
        }

        if (empty($_POST['email']))
        {
            return $this->render('users/edit.html.twig', ['user' => $user, 'error' => 'Merci de bien vouloir entrer un e-mail.']);
        }

        // We remove potential values ​​added by the user
        unset($_POST['is_admin']);
        unset($_POST['validate']);
        unset($_POST['id']);

        unset($_POST['token']);
        unset($_POST['submit']);
        $validator = new FormValidatorHtml($_POST);
        $data = $validator->validate();

        if (!empty($_POST['password']) && !empty($_POST['confirm_password']))
        {
            // Unset the data password and confirm password
            unset($data['password']);
            unset($data['confirm_password']);

            if ($_POST['password'] !== $_POST['confirm_password'])
            {
                return $this->render('users/edit.html.twig', ['user' => $user, 'error' => 'Les mots de passe ne correspondent pas.']);
            }

            // Set the password hash
            $data['password'] = $auth->pwdHash($_POST['password']);
        }

        if ($this->getModel()->update($_SESSION['id'], $data))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        return $this->redirect(BASEURL . '/profil');

    }

    /**
     * Process delete user comment by id
     */
    public function deleteComment(int $id_comment): string|bool 
    {
        $auth = new Auth();
        $model = $this->getModel('comments');

        if (empty($id_comment))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        if (empty($_POST['token']))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        if (!$this->csrf->verif($_POST['token']))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }
        
        if ($model->delete($id_comment))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        return $this->redirect(BASEURL . '/profil');
    }

    /**
     * Process update user comment by id
     */
    public function updateComment(int $id_comment): string|bool
    {
        $auth = new Auth();
        $model = $this->getModel('comments');

        if (empty($id_comment))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        if (empty($_POST['token']))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        if (!$this->csrf->verif($_POST['token']))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        $validator = new FormValidatorHtml($_POST);
        $data = $validator->validate();

        $valdiation = 0;
        if ($auth->isAdmin())
        {
            $valdiation = 1;
        }

        $comment = ['content' => $data['content'], 'validation' => $valdiation];
        if ($model->update($id_comment, $comment))
        {
            return $this->redirect(BASEURL . '/profil');
        }

        return $this->redirect(BASEURL . '/profil');
    }

}
