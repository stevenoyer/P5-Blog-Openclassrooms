<?php 

namespace So\Blog\Controller;

use So\Blog\Auth\Auth;
use So\Blog\Class\Controller;
use So\Blog\HTML\FormValidatorHtml;
use So\Blog\Security\CsrfToken;

class CommentsController extends Controller
{
    /**
     * Process create a new comment of an article by its slug
     */
    public function create(string $slug): string|bool
    {
        $auth = new Auth();
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

        if (!$auth->isConnected())
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

    /**
     * Process update comment by slug article and comment id
     */
    public function update(string $slug, int $id_comment): string|bool
    {
        $auth = new Auth();
        $model = $this->getModel();
        $id = (int) explode('-', $slug, 2)[0];

        if (empty($id))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (empty($id_comment))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (empty($_POST['token']))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (!$this->csrf->verif($_POST['token']))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        $validator = new FormValidatorHtml($_POST);
        $data = $validator->validate();

        $comment = ['content' => $data['content'], 'validation' => 0];
        if ($model->update($id_comment, $comment))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        return $this->redirect(BASEURL . '/article/' . $slug);
    }

    /**
     * Process delete comment by slug article and comment id
     */
    public function delete(string $slug, int $id_comment): string|bool
    {
        $auth = new Auth();
        $model = $this->getModel();
        $id = (int) explode('-', $slug, 2)[0];

        if (empty($id))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (empty($id_comment))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (empty($_POST['token']))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (!$this->csrf->verif($_POST['token']))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }
        
        if ($model->delete($id_comment))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        return $this->redirect(BASEURL . '/article/' . $slug);
    }

}
