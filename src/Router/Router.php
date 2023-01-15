<?php 

namespace So\Blog\Router;

use Exception;

class Router
{
    private array $routes;

    /**
     * This function allows you to retrieve a route
     * @param string $path
     * @param callable|string $action
     * 
     * @return void
     */
    public function get(string $path, callable|string $action): void
    {
        $this->routes[$path] = $action;
    }

    /**
     * This function processes the routes
     * @param string $uri
     */
    public function run(string $uri): mixed
    {
        $path = '/' . explode('?', rtrim($uri))[0];
        $action = $this->routes[$path] ?? null;

        // If callable, return method
        if (is_callable($action))
        {
            return $action();
        }

        // If string, treatment of the controller and the method
        if (is_string($action))
        {
            /**
             * Explode the string, example : HomeController@index
             * HomeController = Controller
             * index = method 
             */
            $explode = explode('@', $action);
            $controller = $explode[0];
            $method = $explode[1];

            // Calling the namespace
            $namespace_controllers = '\So\Blog\Controller\\';

            // Variable setting of the controller with its namespace
            $className = $namespace_controllers . $controller;

            // If the class and method exist, we instantiate the class and call the method
            if (class_exists($className) && method_exists($className, $method))
            {
                $class = new $className();
                $class->name = strtolower(str_replace('Controller', '', $controller));
                $response = call_user_func([$class, $method]);

                echo $response;
                return true;
            }
        }
        
        // Else return Exception
        throw new Exception('This route does not exist.');
    }
}