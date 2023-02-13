<?php 

namespace So\Blog\Controller;

use So\Blog\Class\Controller;

class ArticlesController extends Controller
{
    /**
     * Show articles page
     */
    public function index(): string
    {
        $model = $this->getModel();
        $data = $model->read(0);
        return $this->render('articles/articles.html.twig', ['items' => $data]);
    }

    /**
     * Show single article page
     */
    public function single(string $slug): string
    {
        $id = (int) explode('-', $slug, 2)[0];
        $post = $this->getModel()->find($id);
        $comments = $this->getModel('comments')->findCommentsByPostId($id);

        if (empty($post))
        {
            $this->redirect(BASEURL . '/articles');
        }
        
        return $this->render('articles/article.html.twig', ['post' => $post, 'comments' => $comments]);
    }

}
