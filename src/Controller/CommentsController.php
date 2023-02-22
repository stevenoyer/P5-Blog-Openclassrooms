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

        $validator = new FormValidatorHtml($_POST);
        $data = $validator->validate();

        $valdiation = 0;
        if ($auth->isAdmin())
        {
            $valdiation = 1;
        }

        $comment = ['content' => $data['content'], 'author' => $_SESSION['id'], 'post_id' => $id, 'validation' => $valdiation];
        if ($model->create($comment))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        return $this->redirect(BASEURL . '/article/' . $slug);
    }

    /**
     * Check comments data
     */
    public function checkCommentsData(string $slug, int $id_comment, array $post): bool
    {
        $auth = new Auth();
        $id = (int) explode('-', $slug, 2)[0];

        if (empty($id))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (empty($id_comment))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (empty($post['token']))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (!$this->csrf->verif($post['token']))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        if (!$auth->isConnected())
        {
            return $this->redirect(BASEURL . '/auth');
        }

        return true;
    }

    /**
     * Process update comment by slug article and comment id
     */
    public function update(string $slug, int $id_comment): string|bool
    {
        $auth = new Auth();
        $model = $this->getModel();

        $this->checkCommentsData($slug, $id_comment, $_POST);

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
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        return $this->redirect(BASEURL . '/article/' . $slug);
    }

    /**
     * Process delete comment by slug article and comment id
     */
    public function delete(string $slug, int $id_comment): string|bool
    {
        $model = $this->getModel();
        $id = (int) explode('-', $slug, 2)[0];

        $this->checkCommentsData($id, $id_comment, $_POST);
        
        if ($model->delete($id_comment))
        {
            return $this->redirect(BASEURL . '/article/' . $slug);
        }

        return $this->redirect(BASEURL . '/article/' . $slug);
    }

}
