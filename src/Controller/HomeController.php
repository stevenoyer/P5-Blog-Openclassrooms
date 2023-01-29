<?php 

namespace So\Blog\Controller;

use So\Blog\Class\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $model = $this->getModel();
        $data = $model->read();
        return $this->render('home/index.html.twig', $data);
    }
    
}
