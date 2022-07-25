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

    public function __construct(string $method, string $routeUrl, string|\Closure $target)
    {
        $this->method = strtoupper($method);
        $this->url = trim($routeUrl, '/');
        $this->target = $target;
    }

    public function name(string $name): Route
    {
        $this->name = $name;

        return $this;
    }
}
