<?php

namespace Framework\Http;


class Header
{
    private $name;
    private $value;

    public function __construct($name, $value)
    {
        if(!is_string($name)) {
            throw new \InvalidArgumentException('Header must be a valid string.');
        }

        if(!is_string($value)) {
            throw new \InvalidArgumentException('Header must be a valid string.');
        }

        $this->name = strtolower($name);
        $this->value = $value;
    }

    public function match($name)
    {
        return strtolower($name) === $this->name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public static function createFromString($line)
    {
        $result = preg_match('#^([a-z][a-z0-9-]+)\: (.+)$#i', $line, $header);
        if(!$result) {
            throw new MalformedHttpHeaderException(sprintf('Invalid header line: %s', $line));
        }
        list(, $name, $value) = $header;

        return new self($name, $value);
    }

    public function __toString()
    {
        return sprintf('%s: %s', $this->name, $this->value);
    }

    public function toArray()
    {
        return [$this->name => $this->value];
    }
}