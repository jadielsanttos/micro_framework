<?php

namespace App\Http;

use App\Http\Controllers\NotFoundController;
use App\Http\Request;
use Closure;
use Exception;
use ReflectionFunction;

class Router 
{
    /**
     * @var string $url
     */
    private $url = '';

    /**
     * @var string $prefix
     */
    private $prefix = '';

    /**
     * @var array $routes
     */
    private $routes = [];

    /**
     * @var Request $request
     */
    private $request;

    /**
     * Constructor
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url     = $url;
        $this->request = new Request();
        $this->setPrefix();
    }

    /**
     * Set prefix
     * @return string
     */
    private function setPrefix(): string
    {
        $prefix = parse_url($this->url)['path'];

        $this->prefix = $prefix;

        return $prefix;
    }

    /**
     * Create a route
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function createRoute($method, $route, $params = []): void
    {
        foreach($params as $key => $value) {
            if($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['variables'] = [];

        $variablePattern = '/{(.*?)}/';

        if(preg_match_all($variablePattern, $route, $matches)) {
            $route = preg_replace($variablePattern, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        $routePattern = '/^'.str_replace('/', '\/', $route).'$/';

        $this->routes[$routePattern][$method] = $params;
    }

    /**
     * Get URI
     * @return string
     */
    private function getUri(): string
    {
        $uri = $this->request->getUri();

        $xUri = strlen($uri) ? explode($this->prefix, $uri) : [$uri];

        return end($xUri);
    }

    /**
     * Get current route
     * @return array
     */
    private function getRoute(): array
    {
        $uri = explode('?', $this->getUri())[0];

        $httpMethod = $this->request->getHttpMethod();

        foreach($this->routes as $routePattern => $method) {
            if(preg_match($routePattern, $uri, $matches)) {
                if(isset($method[$httpMethod])) {
                    unset($matches[0]);

                    $keys = $method[$httpMethod]['variables'];
                    $method[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $method[$httpMethod]['variables']['request'] = $this->request;

                    return $method[$httpMethod];
                }
    
                throw new Exception("405, o método enviado não é permitido!", 405);
            }
        }

        $response = new Response(404, NotFoundController::page404());
        return $response->sendResponse();
    }

    /**
     * Run the routes
     * @return Response
     */
    public function run(): Response
    {
        try {
            $route = $this->getRoute();

            if(!$route['controller']) {
                throw new Exception("Essa requisição não pode ser processada", 500);
            }

            $args = [];

            $reflection = new ReflectionFunction($route['controller']);

            foreach($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Register a route get
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = [])
    {
        return $this->createRoute('GET', $route, $params);
    }

    /**
     * Register a route post
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = [])
    {
        return $this->createRoute('POST', $route, $params);
    }
}