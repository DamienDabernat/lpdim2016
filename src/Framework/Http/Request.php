<?php

namespace Framework\Http;

class Request extends AbstractMessage implements RequestInterface
{

    private $method;
    private $path;

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
        parent::__construct($scheme, $schemeVersion, $headers, $body);

        $this->setMethod($method);
        $this->path = $path;
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

    private static function parsePrologue($message)
    {
        $lines = explode(PHP_EOL, $message);
        $result = preg_match('#^(?P<method>[A-Z]{3,7}) (?P<path>.+) (?P<scheme>HTTPS?)\/(?P<version>[1-2]\.[0-2])$#', $lines[0], $matches);
        if(!$result) {
            throw new MalformedHttpMessageException($message, 'HTTP message prologue is malformed');
        }

        return $matches;
    }

    public final static function createFromMessage($message)
    {
        if(!is_string($message) || empty($message)) {
            throw new \MalformedHttpMessageException($message, 'HTTP message is not valid.');
        }

        //1. Parse prologue (first required line)
        $prologue = self::parsePrologue($message);

        //2. Construct new instance of Request class with atomic data
        return new self(
            $prologue['method'],
            $prologue['path'],
            $prologue['scheme'],
            $prologue['version'],
            static::parseHeaders($message), //Parse list of headers (if any)
            static ::parseBody($message) //Parse content (if any)
        );
    }

    protected function createPrologue()
    {
        return sprintf('%s %s %s/%s', $this->method, $this->path, $this->scheme, $this->schemeVersion );
    }


    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }


}