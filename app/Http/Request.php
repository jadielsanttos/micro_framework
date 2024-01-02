<?php

namespace App\Http;

class Request
{
    /**
     * @var string $httpMethod
     */
    private $httpMethod;

    /**
     * @var string $uri
     */
    private $uri;

    /**
     * @var array $queryParams
     */
    private $queryParams = [];

    /**
     * @var array 
     */
    private $postVars = [];

    /**
     * @var array $headers
     */
    private $headers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri         = $_SERVER['REQUEST_URI'] ?? '';
        $this->queryParams = $_GET ?? [];
        $this->postVars    = $_POST ?? [];
        $this->headers     = getallheaders();
    }

    /**
     * Get http method
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Get URI
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get Query Params
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Get Post Vars
     */
    public function getPostVars()
    {
        return $this->postVars;
    }

    /**
     * Get Headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
