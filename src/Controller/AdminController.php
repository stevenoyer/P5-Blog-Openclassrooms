<?php 

namespace So\Blog\Controller;

use So\Blog\Auth\Auth;
use So\Blog\Class\Controller;
use So\Blog\Helper\MailHelper;
use So\Blog\HTML\FormValidatorHtml;

class AdminController extends Controller
{
    /**
     * Method check if user is admin and he is connected
     */
    public function checkAdmin(): bool
    {
        $auth = new Auth();
        if (!$auth->isConnected())
        {
            $this->redirect(BASEURL . '/auth');
            return false;
        }

        if (!$auth->isAdmin())
        {
            $this->redirect(BASEURL . '/auth');
            return false;
        }

        return true;
    }

    public function checkToken(): bool
    {
        if (empty($_POST['token']))
        {
            return $this->redirect(BASEURL . '/admin/articles');
        }

        if (!$this->csrf->verif($_POST['token']))
        {
            return $this->redirect(BASEURL . '/admin/articles');
        }

        return true;
    }

    /**
     * Show admin index
     */
    public function index(): string
    {
        $this->checkAdmin();

        $articles = $this->getModel('articles')->read(5);
        $comments = $this->getModel('comments')->read(5);
        $users = $this->getModel('users')->read(5);
        
        $stats = [
            'articles' => count($this->getModel('articles')->read(0, null)),
            'comments' => count($this->getModel('comments')->read(0, null)),
            'users' => count($this->getModel('users')->read(0))
        ];

        return $this->render('admin/index.html.twig', ['articles' => $articles, 'comments' => $comments, 'users' => $users, 'stats' => $stats]);
    }

    /**
     * Show articles page
     */
    public function articles(): string
    {
        $this->checkAdmin();

        $model = $this->getModel('articles');
        $data = $model->read(0, null);
        
        return $this->render('admin/articles/list.html.twig', ['items' => $data]);
    }

    /**
     * Show comments page
     */
    public function comments(): string
    {
        $this->checkAdmin();
        $data = $this->getModel('comments')->read(0, null);
        return $this->render('admin/comments/list.html.twig', ['items' => $data]);
    }

    /**
     * Show users page
     */
    public function users(): string
    {
        $this->checkAdmin();
        $data = $this->getModel('users')->read(0, null);
        return $this->render('admin/users/list.html.twig', ['items' => $data]);
    }

    /**
     * Generate slug by title
     */
    public function generateSlugByTitle(string $title): string
    {
        $slug = iconv('UTF-8', 'US-ASCII//TRANSLIT', $title);
        $slug = preg_replace('/[^a-zA-Z0-9\s]/', '', $slug);
        $slug = strtolower(str_replace(' ', '-', $slug));
        return $slug;
    }

    /**
     * Check item data
     */
    public function checkPostArticle(array $post, bool $edit = false): string|array
    {
        $this->checkToken();

        if (empty($post['slug']))
        {
            $post['slug'] = $this->generateSlugByTitle($post['title']);
        }

        $validator = new FormValidatorHtml($post);
        if (!$validator->checkEmpty())
        {
            return $this->render('admin/articles/edit.html.twig', ['error' => 'Veuillez vérifier toutes les informations du formulaire.']);
        }

        $data = $validator->validate();
        $articles_exists = $this->getModel('articles')->find($data['slug'], 'slug', false);
        
        if (!empty($articles_exists))
        {
            if (!$edit)
            {
                return $this->render('admin/articles/edit.html.twig', ['error' => 'Un article existe déjà avec ce slug.', 'item' => $data]);
            }
        }

        return [
            'title' => $data['title'],
            'state' => $data['state'],
            'slug' => $data['slug'],
            'introtext' => $data['introtext'],
            'content' => $data['content'],
            'author' => $_SESSION['id'],
        ];
    }

    public function createArticle(): string|bool
    {
        $this->checkAdmin();

        if (!empty($_POST))
        {
            $this->checkToken();

            $article = $this->checkPostArticle($_POST, false);
            if (is_array($article))
            {
                if ($this->getModel('articles')->create($article))
                {
                    return $this->redirect(BASEURL . '/admin/articles');
                }
            }

            if (is_string($article))
            {
                return $article;
            }

        }

        return $this->render('admin/articles/edit.html.twig', []);
    }

    /**
     * Show edit article page by id 
     */
    public function editArticle(?int $id = null): string|bool
    {
        $this->checkAdmin();

        if (is_null($id))
        {
            $this->redirect(BASEURL . '/admin/articles');
            return false;
        }

        if (!empty($_POST))
        {
            $this->checkToken();

            $article = $this->checkPostArticle($_POST, true);
            if (is_array($article))
            {
                if ($this->getModel('articles')->update($id, $article))
                {
                    $article['id'] = $id;
                    return $this->render('admin/articles/edit.html.twig', ['item' => $article]);
                }
            }

            if (is_string($article))
            {
                return $article;
            }

        }

        $article = $this->getModel('articles')->find($id, 'id');
        return $this->render('admin/articles/edit.html.twig', ['item' => $article]);
    }

    /**
     * Delete the article
     */
    public function deleteArticle(): bool
    {
        $this->checkAdmin();
        $this->checkToken();

        if (empty($_POST['id']))
        {
            $this->redirect(BASEURL . '/admin/articles');
        }

        // Get comments
        $comments = $this->getModel('comments')->findCommentsByPostId((int) $_POST['id']);
        foreach ($comments as $comment)
        {
            $this->getModel('comments')->delete($comment->id);
        }

        $this->getModel('articles')->delete($_POST['id']);
        return $this->redirect(BASEURL . '/admin/articles');
    }

    /**
     * Update the article status
     */
    public function updateStateArticle(): bool
    {
        $this->checkAdmin();
        $this->checkToken();

        if (empty($_POST['id']))
        {
            return $this->redirect(BASEURL . '/admin/articles');
        }

        if (!isset($_POST['state']))
        {
            return $this->redirect(BASEURL . '/admin/articles');
        }

        $this->getModel('articles')->update($_POST['id'], ['state' => $_POST['state']]);
        return $this->redirect(BASEURL . '/admin/articles');
    }

    /**
     * Update the comment status
     */
    public function updateStateComment(): bool
    {
        $this->checkAdmin();
        $this->checkToken();

        if (empty($_POST['id']))
        {
            return $this->redirect(BASEURL . '/admin/comments');
        }

        if (!isset($_POST['validation']))
        {
            return $this->redirect(BASEURL . '/admin/comments');
        }

        $this->getModel('comments')->update($_POST['id'], ['validation' => $_POST['validation']]);
        return $this->redirect(BASEURL . '/admin/comments');
    }

    /**
     * Delete the comment
     */
    public function deleteComment(): bool
    {
        $this->checkAdmin();
        $this->checkToken();

        if (empty($_POST['id']))
        {
            $this->redirect(BASEURL . '/admin/comments');
        }

        $this->getModel('comments')->delete($_POST['id']);
        return $this->redirect(BASEURL . '/admin/comments');
    }

    /**
     * Delete the user
     */
    public function deleteUser(): bool
    {
        $this->checkAdmin();
        $this->checkToken();

        if (empty($_POST['id']))
        {
            $this->redirect(BASEURL . '/admin/users');
        }

        $this->getModel('users')->delete($_POST['id']);
        return $this->redirect(BASEURL . '/admin/users');
    }

    /**
     * Update the user role
     */
    public function updateUserRole(): bool
    {
        $this->checkAdmin();
        $this->checkToken();

        if (empty($_POST['id']))
        {
            return $this->redirect(BASEURL . '/admin/users');
        }

        if (!isset($_POST['is_admin']))
        {
            return $this->redirect(BASEURL . '/admin/users');
        }

        $this->getModel('users')->update($_POST['id'], ['is_admin' => $_POST['is_admin']]);
        return $this->redirect(BASEURL . '/admin/users');
    }

    /**
     * Update user account validation
     */
    public function updateUserValidation(): bool
    {
        $this->checkAdmin();
        $this->checkToken();

        if (empty($_POST['id']))
        {
            return $this->redirect(BASEURL . '/admin/users');
        }

        if (!isset($_POST['validate']))
        {
            return $this->redirect(BASEURL . '/admin/users');
        }

        $this->getModel('users')->update($_POST['id'], ['validate' => $_POST['validate']]);
        return $this->redirect(BASEURL . '/admin/users');
    }

    /**
     * Show add user page
     */
    public function editUser(): string
    {
        $this->checkAdmin();
        
        if (!empty($_POST))
        {
            $validator = new FormValidatorHtml($_POST);
            if (!$validator->checkEmpty())
            {
                return $this->render('admin/users/edit.html.twig', ['error' => 'Veuillez vérifier toutes les informations du formulaire.', $_POST]);
            }

            $data = $validator->validate();
            if (!empty($data['error']) && $data['error'] && $data['email'])
            {
                return $this->render('auth/auth.html.twig', ['error' => ['user' => 'Le format de l\'e-mail est incorrect.', 'email' => $data['email']]]);
            }

            $user = $this->getModel('users')->findByMail($data['email']);
            if (!empty($user))
            {
                return $this->render('auth/auth.html.twig', ['error' => 'Cet e-mail a déjà été utilisée.', $data]);
            }

            $user = [
                'name' => $data['name'],
                'email' => $data['email'],
                'validate' => $data['validate'],
                'is_admin' => $data['is_admin'],
                'password' => $_POST['password'],
                'token_validation' => sha1(time()),
                'token_expiration' => time() + 7200
            ];
            
            $auth = new Auth();
            if ($auth->register($user))
            {
                $helper = new MailHelper;
                if ($helper->sendMailConfirmation($user, true))
                {
                    return $this->redirect(BASEURL . '/admin/users');
                }

                return $this->render('auth/auth.html.twig', ['error' => 'Une erreur est survenue lors de l\'envoi du mail de confirmation.', $data]);
            }
        }

        return $this->render('admin/users/edit.html.twig', []);
    }

}
