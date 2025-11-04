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


    public function route(string $uri): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $route){

            //split the current uri into segments
            $uriSegments = explode("/", trim($uri, '/'));

            //Split the route uri into segments
            $routeSegments = explode("/", trim($route['uri'], '/'));

            $match = true;
            //check if number of segments match
            if(count($uriSegments) === count($routeSegments) && strtoupper($route['method'] === $requestMethod)){
                $params =[];
                for($i = 0; $i < count($uriSegments); $i++){

                    if($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/',$routeSegments[$i] )){
                        $match = false;
                        break;

                        //check for the params andadd to the $params array
                    }  if(preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)){
                        $params[$matches[1]] = $uriSegments[$i] ;
                    }
                }

                if($match){
                    $controller = 'App\\Controllers\\' . $route['controller'] ;
                    $controllerMethod = $route['controllerMethod'];

                    //Instantiate the controller and call the method

                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            }

        }


     ErrorController::notFound();
    }
}