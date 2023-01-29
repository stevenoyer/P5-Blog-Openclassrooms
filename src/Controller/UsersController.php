<?php 

namespace So\Blog\Controller;

use So\Blog\Auth\Auth;
use So\Blog\Class\Controller;

class UsersController extends Controller
{
    public function profil()
    {
        $auth = new Auth();
        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        $user = $this->getModel()->find($_SESSION['id']);
        return $this->render('users/profil.html.twig', ['user' => $user]);
    }

}
