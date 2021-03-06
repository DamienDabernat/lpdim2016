<?php

namespace Framework\Routing;

class RouteCollection implements \Iterator, \Countable
{
    /**
     * @var Route[]
     */
    private $routes;

    /**
     * RouteCollection constructor.
     * @param $routes
     */
    public function __construct()
    {
        $this->routes = [];
    }


    /**
     * @param $path
     * @return mixed
     */
    public function match($path)
    {
        foreach($this->routes as $route) {
            if($route->match($path)) {
                return $route;
            }
        }
    }

    public function add($name, $route, $override = false)
    {
        if(isset($this->routes[$name]) && !$override) {
            throw new \InvalidArgumentException(sprintf('A route already exists for the name "%s".',
                $name));
        }

        $this->routes[$name] = $route;
    }

    public function merge(RouteCollection $routes, $override = false)
    {
        foreach($routes as $name => $route) {
            $this->add($name, $route, $override);
        }
    }

    public function current()
    {
        return current($this->routes);
    }

    public function next()
    {
        return next($this->routes);
    }

    public function key()
    {
        return key($this->routes);
    }

    public function valid()
    {
        return $this->current() instanceof Route;
    }

    public function rewind()
    {
        reset($this->routes);
    }

    public function count()
    {
        return count($this->routes);
    }
}