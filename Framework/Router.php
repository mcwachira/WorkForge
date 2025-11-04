<?php

namespace  Framework;
use App\Controllers\ErrorController;

class Router
{
    protected array $routes = [];


    /**
     * Add a new route
     * @param $method
     * @param $uri
     * @param $action
     * @return void
     */
    public function registerRoute ($method, $uri, $action): void
    {

        list($controller, $controllerMethod) = explode('@', $action);

    $this->routes[] =[
        'method' =>$method,
        'uri' => $uri,
        'controller' => $controller,
        'controllerMethod' => $controllerMethod,
    ];
}

    /**
     * Add a GET route
     * @parms string $uri
     * @params string $controller
     * @param $uri
     * @param $controller
     * @return void
     */

    public function get($uri, $controller): void
    {

      $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Add a POST route
     * @parms string $uri
     * @params string $controller
     * @param $uri
     * @param $controller
     * @return void
     */

    public function post($uri, $controller): void
    {
        $this->registerRoute('POST', $uri, $controller);
    }

    /**
     * Add a  PUT route
     * @parms string $uri
     * @params string $controller
     * @param $uri
     * @param $controller
     * @return void
     */

    public function put($uri, $controller): void
    {
      $this->registerRoute('    PUT', $uri, $controller);
    }

    /**
     * Add a  Delete route
     * @parms string $uri
     * @params string $controller
     * @param $uri
     * @param $controller
     * @return void
     */

    public function delete($uri, $controller): void
    {
  $this->registerRoute('DELETE', $uri, $controller);
    }




    /**
     * Route the Request
     * @param string $uri
     * @param string $method
     * @return void
     */


    public function route(string $uri, string $method): void
    {
        foreach ($this->routes as $route){
            if($route['uri'] === $uri && $route['method'] === $method){
                //extract controller and controller method

                $controller = 'App\\Controllers\\' . $route['controller'] ;
                $controllerMethod = $route['controllerMethod'];

                //Instantiate the controller and call the method

                $controllerInstance = new $controller();
                $controllerInstance->$controllerMethod();
                return;
            }
        }


     ErrorController::notFound();
    }
}