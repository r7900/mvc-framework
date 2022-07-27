<?php

namespace Core;

class Route
{
    public string $url;
    /**
     * Request method i.e GET,POST,PUT,DELETE,...
     */
    public string $method;

    /**
     * string: 2 strings seperated with '@' i.e "ControllerClass@targetMethod" for routing to a method inside a controller. or 1 string without '@' i.e "viewFile" for routing directly to view . 
     * 
     * Closure: An anonymous function that will call instead of calling controller .
     * 
     */
    public string|\Closure $target;
    public string $name = '';
    private array $middlewares = [];

    public function __construct(string $method, string $routeUrl, string|\Closure $target, bool $isApi = false)
    {
        $this->method = strtoupper($method);
        $this->url = trim($routeUrl, '/');
        if ($isApi) {
            $this->url = 'api/' . $this->url;
        }
        $this->target = $target;
    }

    public function name(string $name): Route
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array|string $value Name of the route middleware class (located in app/middlewares/route/) or an array of middlewares
     */
    public function middleware($value)
    {
        if (is_array($value)) {
            foreach ($value as &$middleware) {
                $this->middlewares[] = 'App\\Middlewares\\Route\\' . $middleware;
            }
            return $this;
        }
        $this->middlewares[] = 'App\\Middlewares\\Route\\' . $value;

        return $this;
    }

    /**
     * @return array $this->middlewares
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
}
