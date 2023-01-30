<?php 

namespace So\Blog\Controller;

use So\Blog\Class\Controller;

class HomeController extends Controller
{
    public function index(): string
    {
        $model = $this->getModel('articles');
        $data = $model->read(3);
        
        return $this->render('home/index.html.twig', ['items' => $data]);
    }
    
}
