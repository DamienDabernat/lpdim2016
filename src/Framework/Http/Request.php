<?php

namespace Framework\Http;

class Request {

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const OPTION = 'OPTION';
    const TRACE = 'TRACE';
    const HEAD = 'HEAD';
    const DELETE = 'DELETE';
    const CONNECT = 'CONNECT';

    const HTTP = 'HTTP';
    const HTTPS = 'HTTPS';

    private $method;
    private $path;
    private $scheme;
    private $schemeVersion;
    private $headers;
    private $body;

    /**
     * @param $method           The http verb
     * @param $path             The resource path on the server
     * @param $scheme           The protocole name (HTTP or HTTPS)
     * @param $schemeVersion    The scheme version (ie: 1.0, 1.1 or 2.0)
     * @param array $headers    An associative array of headers
     * @param string $body      The request content
     */
    public function __construct($method, $path, $scheme, $schemeVersion, array $headers = [], $body = '')
    {
        $this->setMethod($method);
        $this->path = $path;
        $this->scheme = $scheme;
        $this->schemeVersion = $schemeVersion;
        $this->headers = $headers;
        $this->body = $body;
    }

    private function setMethod($method)
    {
        $methods = [
            self::GET,
            self::POST,
            self::PUT,
            self::PATCH,
            self::OPTION,
            self::TRACE,
            self::HEAD,
            self::DELETE,
            self::CONNECT,
        ];

        if(!in_array($method, $methods)) {
            throw new \InvalidArgumentException(sprintf('Method %s is not a supported HTTP method and must be one of %s.',
                $method,
                implode(',', $methods)
            ));
        }

        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getSchemeVersion()
    {
        return $this->schemeVersion;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

}