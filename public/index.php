<?php

use So\Blog\Class\Controller;
use So\Blog\Router\Router;
use Tracy\Debugger;

define('ROOT', dirname(__DIR__));
define('CONFIG', dirname(__DIR__) . '/configuration.php');
define('BASEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER["REQUEST_URI"] . '?'));

// Require autoload
require '../vendor/autoload.php';
require CONFIG;

// Call Config & controller
$config = new Config();
$ctrl = new Controller;

if ($config->debug)
{
    Debugger::enable();
    Debugger::$strictMode = true;
}

// Calling the router and defining routes
$router = new Router();
$router->get('/', 'HomeController@index');
$router->get('/articles', 'ArticlesController@show');

// Processing the url
$explode_uri = explode('/', $_SERVER['REQUEST_URI']);
if (is_array($explode_uri) && !empty($explode_uri))
{
    $uri = explode('/', $_SERVER['REQUEST_URI']);
    $uri = end($uri);
}

try 
{
    $router->run($uri);
}
catch (\Exception $e) 
{
    $ctrl->notFound();
    exit;
}