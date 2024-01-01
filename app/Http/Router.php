<?php

namespace App\Http;

use App\Http\Request;
use Closure;
use Exception;
use ReflectionFunction;

class Router 
{
    private $url = '';

    private $prefix = '';

    private $routes = [];

    private $request;

    public function __construct($url)
    {
        $this->url     = $url;
        $this->request = new Request();
        $this->setPrefix();
    }

    private function setPrefix()
    {
        $prefix = parse_url($this->url)['path'];

        $this->prefix = $prefix;
    }

    private function createRoute($method, $route, $params = [])
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

    private function getUri()
    {
        $uri = $this->request->getUri();

        $xUri = strlen($uri) ? explode($this->prefix, $uri) : [$uri];

        return end($xUri);
    }

    private function getRoute()
    {
        $uri = $this->getUri();

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
    
                throw new Exception("Método não permitido!", 405);
            }
        }

        throw new Exception("404, Página não encontrada!", 404);
    }

    public function run()
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

    public function get($route, $params = [])
    {
        return $this->createRoute('GET', $route, $params);
    }

    public function post($route, $params = [])
    {
        return $this->createRoute('POST', $route, $params);
    }
}