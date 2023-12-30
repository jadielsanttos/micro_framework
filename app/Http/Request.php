<?php

namespace App\Http;

class Request
{
    private $httpMethod;

    private $uri;

    private $queryParams = [];

    private $data = [];

    private $headers;

    public function __construct()
    {
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri         = $_SERVER['REQUEST_URI'] ?? '';
        $this->queryParams = $_GET ?? [];
        $this->data        = $_POST ?? [];
        $this->headers     = getallheaders();
    }

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
