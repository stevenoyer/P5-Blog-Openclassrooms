<?php

use So\Blog\Auth\Auth;
use So\Blog\Class\Controller;
use So\Blog\Router\Router;
use Tracy\Debugger;

define('ROOT', dirname(__DIR__));
define('CONFIG', dirname(__DIR__) . '/configuration.php');
define('BASEURL', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'));

// Require autoload
require '../vendor/autoload.php';
require CONFIG;

// Call Config & controller
$auth = new Auth;
$config = new Config();
$ctrl = new Controller;

if ($config->debug)
{
    Debugger::enable();
    Debugger::$strictMode = true;
}

// Calling the router and defining routes
$router = new Router();

// Articles
$router->get('/', 'HomeController@index');
$router->get('/articles', 'ArticlesController@index');
$router->get('/article/:id', 'ArticlesController@single');
$router->post('/article/:id/comments', 'CommentsController@create');

// Authentication
$router->get('/auth', 'AuthController@index');
$router->get('/logout', 'AuthController@logout');
$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');

// Users
$router->get('/profil', 'UsersController@profil');


// Processing the url
$uri = str_replace(dirname($_SERVER['PHP_SELF']), '', $_SERVER['REQUEST_URI']);

try 
{
    $auth->init();
    $router->run($uri);
}
catch (\Exception $e) 
{
    $ctrl->notFound($e->getMessage());
    exit;
}
