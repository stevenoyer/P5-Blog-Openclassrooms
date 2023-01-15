<?php 

namespace So\Blog\Class;

use Exception;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class Controller
{
    private $loader;
    protected $templatesPath = ROOT . '/views';
    protected $twig;
    public string $name;

    /**
     * This class allows you to call the model and return it
     * @return Class
     */
    public function getModel()
    {
        $model_name = '\So\Blog\Model\\' . ucfirst($this->name) . 'Model';
        return new $model_name();
    }

    public function __construct()
    {
        // Config templates folder in twig
        $this->loader = new FilesystemLoader($this->templatesPath);

        // Config environment twig
        $this->twig = new Environment($this->loader, ['cache' => false, 'debug' => true]);
        $this->twig->addExtension(new DebugExtension);
    }

    /**
     * Method that renders the template
     * @param string|null $tmpl
     * @param array $data
     * @return Environment render twig
     */
    public function render(string $tmpl = null, array $data = [])
    {
        if (is_null($tmpl))
        {
            $tmpl = 'home/index.html.twig';
        }

        $data['baseurl'] = BASEURL;
        return $this->twig->render($tmpl, $data);
    }

    /**
     * Method of redirecting to a url
     * @param string $url
     */
    public function redirect(string $url)
    {
        try 
        {
            if (empty($url)) return new Exception('Url is not defined');
            header('Location: ' . $url);
        } 
        catch (\Exception $e) 
        {
            die($e->getMessage());
        }
    }

    /**
     * Method forbiden, setting up the header and calling the template
     */
    public function forbiden()
    {
        header('HTTP/1.0 403 Forbiden');
        echo $this->render('errors/403.html.twig');
        exit;
    }

    /**
     * Method not found, setting up the header and calling the template
     */
    public function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        echo $this->render('errors/404.html.twig');
        exit;
    }
}