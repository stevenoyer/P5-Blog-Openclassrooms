<?php 

namespace So\Blog\Controller;

use So\Blog\Class\Controller;
use So\Blog\HTML\FormValidatorHtml;
use So\Blog\Security\CsrfToken;

class CommentsController extends Controller
{
    public function create(string $slug): string
    {
        $id = (int) explode('-', $slug, 2)[0];

        if (empty($id))
        {
            return $this->redirect(BASEURL . '/articles');
        }

        $model = $this->getModel();
        if (empty($_POST['token']))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (!$this->csrf->verif($_POST['token']))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (empty($_SESSION['id']))
        {
            return $this->redirect(BASEURL . '/auth');
        }

        unset($_POST['token']);
        $validator = new FormValidatorHtml($_POST);
        $data = $validator->validate();

        $comment = ['content' => $data['content'], 'author' => $_SESSION['id'], 'post_id' => $id, 'validation' => 0];
        if ($model->create($comment))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        return $this->redirect(BASEURL . '/article/' . $slug);
    }

}
