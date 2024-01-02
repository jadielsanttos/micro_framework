<?php

namespace App\Http;

class Response
{
    /**
     * @var int $httpCode
     */
    private $httpCode = 200;

    /**
     * @var array $headers
     */
    private $headers = [];

    /**
     * @var mixed $content
     */
    private $content;

    /**
     * @var string $contentType
     */
    private $contentType;

    /**
     * Constructor
     * @param int $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode    = $httpCode;
        $this->content     = $content;
        $this->setContentType($contentType);
    }

    /**
     * Set Content Type Of Response
     * @param string $contentType
     * @return void
     */
    public function setContentType($contentType): void
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Add header
     * @param string $key
     * @param string $value
     * @return void
     */
    public function addHeader($key, $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * Send Headers
     * @return void
     */
    private function sendHeaders(): void
    {
        http_response_code($this->httpCode);

        foreach($this->headers as $key => $value) {
            header($key.': '.$value);
        }
    }

    /**
     * Send Response
     * @return void
     */
    public function sendResponse(): void
    {
        $this->sendHeaders();

        switch($this->contentType) {
            case 'text/html':
                echo $this->content;
            exit;
        }
    }
}