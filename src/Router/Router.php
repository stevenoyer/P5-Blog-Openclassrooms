<?php 

namespace So\Blog\Router;

use Exception;

class Router
{
    private array $routes = [];
    private array $matches = [];
    private string $namespace_controllers = '\So\Blog\Controller\\';
    private mixed $action;

    /**
     * This function allows you to retrieve a route
     * @param string $path
     * @param callable|string $action
     * 
     * @return callable|string
     */
    public function post(string $path, callable|string $action): callable|string
    {
        return $this->add($path, $action, 'POST');
    }

    /**
     * This function allows you to retrieve a route
     * @param string $path
     * @param callable|string $action
     * 
     * @return callable|string
     */
    public function get(string $path, callable|string $action): callable|string
    {
        return $this->add($path, $action, 'GET');
    }

    /**
     * Function that adds a new route to the routes
     * @param string $path
     * @param callable|string $action
     * @param string $method
     * 
     * @return callable|string
     */
    public function add(string $path, callable|string $action, string $method): callable|string
    {
        return $this->routes[$method][$path] = $action;
    }

    /**
     * Function that matches a route with the URL, if they match, then true is returned otherwise false
     * @param string $path
     * @param string $uri
     * 
     * @return bool
     */
    public function match(string $path, string $uri): bool
    {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $path);
        $regex = "#^$path$#i";

        if (!preg_match($regex, $uri, $matches))
        {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Call the callable or controller with method
     * @return mixed
     */
    public function call(): mixed
    {
        // If callable, return method
        if (is_callable($this->action))
        {
            return call_user_func_array($this->action, $this->matches);
        }

        // If string, treatment of the controller and the method
        if (is_string($this->action))
        {
            /**
             * Explode the string, example : HomeController@index
             * HomeController = Controller
             * index = method 
             */
            $explode = explode('@', $this->action);
            $controller = $explode[0];
            $method = $explode[1];

            // Variable setting of the controller with its namespace
            $className = $this->namespace_controllers . $controller;

            // If the class and method exist, we instantiate the class and call the method
            if (class_exists($className) && method_exists($className, $method))
            {
                $class = new $className();
                $class->name = strtolower(str_replace('Controller', '', $controller));
                return call_user_func_array([$class, $method], $this->matches);
            }
        }

        return false;
    }

    /**
     * This function processes the routes
     * @param string $uri
     * @return bool
     */
    public function run(string $uri): bool
    {
        $this->action = false;

        // We loop on all routes
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $path => $route)
        {
            // Checks if the url matches a route
            if ($this->match($path, $uri))
            {
                // Implementation of the action
                $this->action = $this->routes[$_SERVER['REQUEST_METHOD']][$path] ?? null;

                echo $this->call();
                return true;
            }
        }
        
        // Else return Exception
        throw new Exception('This route does not exist.');
    }
}