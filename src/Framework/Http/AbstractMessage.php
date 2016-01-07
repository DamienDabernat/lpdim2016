<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 06/01/2016
 * Time: 11:35
 */

namespace Framework\Http;


abstract class AbstractMessage implements MessageInterface
{
    protected $scheme;
    protected $schemeVersion;

    /**
     * A collection of Header instances.
     *
     * @var Header[]
     */
    protected $headers;
    protected $body;

    public function __construct($scheme, $schemeVersion, array $headers = [], $body = '')
    {
        $this->headers = [];
        $this->setScheme($scheme);
        $this->setSchemeVersion($schemeVersion);
        $this->setHeaders($headers);
        $this->body = $body;
    }

    /**
     * @param $line
     * @param $position
     * @return array
     */
    private static function parseHeader($line, $position)
    {
        try {
            return Header::createFromString($line)->toArray();
        } catch (MalformedHttpHeaderException $e) {
            throw new MalformedHttpHeaderException(sprintf('Invalid header line at position %u: %s', $i + 2, $line),
                0,
                $e
            );
        }
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getSchemeVersion()
    {
        return $this->schemeVersion;
    }

    private function setHeaders(array $headers)
    {
        foreach ($headers as $header => $value) {
            $this->addHeader($header, $value);
        }
    }

    public function getHeader($name)
    {
        if ($header = $this->findHeader($name)) {
            return $header->getValue();
        }
    }

    /**
     * Add a new normalized header value ti he list of all headers.
     * @param $header
     * @param $name
     *
     * @throws \RuntimeException
     */
    private function addHeader($name, $value)
    {
        if($this->findHeader($name)) {
            throw new \RuntimeException(sprintf('Header %s is already defined and cannot be set twice.', $name));
        }

        $this->headers[] = new Header($name, (string) $value);
    }

    /**
     * Returns the corresponding Header instance
     * @param string $name
     * @return Header
     */
    private function findHeader($name)
    {
        foreach($this->headers as $header) {
            if($header->match($name)) {
                return $header;
            }
        }
    }

    private function setSchemeVersion($version)
    {
        $versions = [
            self::VERSION_1_0,
            self::VERSION_1_1,
            self::VERSION_2_0,
        ];

        if(!in_array($version, $versions)) {
            throw new \InvalidArgumentException(sprintf('SchemeVersion %s is not a supported schemeVersion and must be one of %s.',
                $version,
                implode(',', $versions)
            ));
        }

        $this->schemeVersion = $version;
    }

    private function setScheme($scheme)
    {
        $schemes = [
            self::HTTP,
            self::HTTPS,
        ];

        if(!in_array($scheme, $schemes)) {
            throw new \InvalidArgumentException(sprintf('Scheme %s is not a supported scheme and must be one of %s.',
                $scheme,
                implode(',', $schemes)
            ));
        }

        $this->scheme = $scheme;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach($this->headers as $header) {
            $headers = array_merge($headers, $header->toArray());
        }

        return $headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    protected abstract function createPrologue();

    /*
     * Returns the message instance as an HTTP string representation
     * @return string
     */
    final public function getMessage() {

        $message = $this->createPrologue();

        if(count($this->headers)) {
            $message.= PHP_EOL;
            foreach($this->headers as $header) {
                $message .= $header.PHP_EOL;
            }
        }

        $message .= PHP_EOL;
        if($this->body) {
            $message .= $this->body;
        }

        return $message;
    }

    /**
     * @return string representation of a message instance.
     *
     * Alias of getMessage();
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

    protected static function parseBody($message)
    {
        $pos = strpos($message, PHP_EOL.PHP_EOL);

        return (string) substr($message, $pos+4);
    }

    protected static function parseHeaders($message)
    {
        $start = strpos($message, PHP_EOL) + 2;
        $end = strpos($message, PHP_EOL.PHP_EOL);
        $length = $end - $start;
        $lines = explode(PHP_EOL, substr($message, $start, $length));

        //Parse list of headers (if any)
        $i = 0;
        $headers = [];
        while(!empty($lines[$i])) {
            $headers = array_merge($headers, static::parseHeader($lines[$i], $i));
            $i++;
        }

        return $headers;
    }
}