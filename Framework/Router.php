<?php

namespace  Framework;
use App\Controllers\ErrorController;
use Framework\middleware\Authorize ;
class  Router
{
    protected array $routes = [];


    /**
     * Add a new route
     * @param $method
     * @param $uri
     * @param $action
     * @param array $middleware
     * @return void
     */
    public function registerRoute ($method, $uri, $action, array $middleware=[]): void
    {

        list($controller, $controllerMethod) = explode('@', $action);

    $this->routes[] =[
        'method' =>$method,
        'uri' => $uri,
        'controller' => $controller,
        'controllerMethod' => $controllerMethod,
        'middleware' => $middleware
    ];
}

    /**
     * Add a GET route
     * @parms string $uri
     * @params string $controller
     * @param $uri
     * @param $controller
     * @param array $middleware
     * @return void
     */

    public function get($uri, $controller, array $middleware=[]): void
    {

      $this->registerRoute('GET', $uri, $controller, $middleware   );
    }

    /**
     * Add a POST route
     * @parms string $uri
     * @params string $controller
     * @param $uri
     * @param $controller
     * @param array $middleware
     * @return void
     */

    public function post($uri, $controller, array $middleware=[] ): void
    {
        $this->registerRoute('POST', $uri, $controller, $middleware );
    }

    /**
     * Add a  PUT route
     * @parms string $uri
     * @params string $controller
     * @param $uri
     * @param $controller
     * @param array $middleware
     * @return void
     */

    public function put($uri, $controller, array $middleware=[] ): void
    {
      $this->registerRoute('PUT', $uri, $controller, $middleware );
    }

    /**
     * Add a  Delete route
     * @parms string $uri
     * @params string $controller
     * @param $uri
     * @param $controller
     * @param array $middleware
     * @return void
     */

    public function delete($uri, $controller, array $middleware=[] ): void
    {
  $this->registerRoute('DELETE', $uri, $controller, $middleware );
    }




    /**
     * Route the Request
     * @param string $uri
 * @param string $method
     *  @param array $middleware
     * @return void
     */


    public function route(string $uri): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

            //check for _method input
        if($requestMethod === "POST" && isset($_POST['_method'])) {
            //override the request method with the value of the _method
            $requestMethod = strtoupper($_POST['_method']);
        }

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
                    foreach($route['middleware'] as $middleware){
                        (new Authorize())->handle($middleware);
                    }
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