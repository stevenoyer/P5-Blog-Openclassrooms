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
$config = new Config;
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
$router->post('/article/:id/comments/:id', 'CommentsController@update');
$router->post('/article/:id/comments/:id/delete', 'CommentsController@delete');

// Authentication
$router->get('/auth', 'AuthController@index');
$router->get('/logout', 'AuthController@logout');
$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');

// Users
$router->get('/profil', 'UsersController@profil');
$router->get('/profil/edit', 'UsersController@edit');
$router->post('/profil/edit', 'UsersController@updateProfil');
$router->post('/profil/updateComment/:id', 'UsersController@updateComment');
$router->post('/profil/deleteComment/:id', 'UsersController@deleteComment');

// Admin
$router->get('/admin', 'AdminController@index');
$router->get('/admin/articles', 'AdminController@articles');
$router->get('/admin/articles/new', 'AdminController@editArticle');
$router->get('/admin/articles/edit/:id', 'AdminController@editArticle');
$router->get('/admin/articles/delete', 'AdminController@deleteArticle');
// POST
$router->post('/admin/articles/new', 'AdminController@editArticle');
$router->post('/admin/articles/edit/:id', 'AdminController@editArticle');
$router->post('/admin/articles/delete', 'AdminController@deleteArticle');


$router->get('/admin/comments', 'AdminController@comments');
$router->get('/admin/comments/new', 'AdminController@editComment');
$router->get('/admin/comments/edit/:id', 'AdminController@editComment');
$router->get('/admin/comments/delete', 'AdminController@deleteComment');
// POST
$router->post('/admin/comments/new', 'AdminController@editComment');
$router->post('/admin/comments/edit/:id', 'AdminController@editComment');
$router->post('/admin/comments/delete', 'AdminController@deleteComment');


$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/users/delete', 'AdminController@deleteUser');
$router->get('/admin/users/new', 'AdminController@editUser');
// POST
$router->post('/admin/users/new', 'AdminController@editUser');
$router->post('/admin/users/delete', 'AdminController@deleteUser');

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
