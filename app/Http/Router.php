<?php

namespace App\Http;

use App\Http\Request;
use Closure;
use Exception;

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
        $routePattern = '/^'.str_replace('/', '\/', $route).'$/';

        foreach($params as $key => $value) {
            if($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

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
            if(preg_match($routePattern, $uri)) {
                if($method[$httpMethod]) {
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

            // ARGUMENTOS DA FUNÇÃO
            $args = [];

            // RETORNA A EXECUÇÃO DA FUNÇÃO
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