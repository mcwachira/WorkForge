<?php

class Router
{
    protected array $routes = [];


    /**
     * Add a new route
     * @param $method
     * @param $uri
     * @param $controller
     * @return void
     */
    public function registerRoute ($method, $uri, $controller): void
    {
    $this->routes[] =[
        'method' =>$method,
        'uri' => $uri,
        'controller' => $controller
    ];
}

    /**
     * Add a GET route
     * @parms string $uri
     * @params string $controller
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
     * @return void
     */

    public function delete($uri, $controller): void
    {
  $this->registerRoute('DELETE', $uri, $controller);
    }

    /**
     * Load error Page
     * @param int $httpCode
     *
     * @return void
     */

    public function error ($httpCode =404){
        http_response_code($httpCode);
        loadView("error/$httpCode");
        exit;
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
                require basePath('App/' . $route['controller']);
                return;
            }
        }


        $this->error();
    }
}