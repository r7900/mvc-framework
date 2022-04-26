<?php

namespace Core;

use Core\View;

abstract class Controller
{
    protected $parameters;
    protected $requestMethod;
    public function __construct($param, $method)
    {
        $this->parameters = $param;
        $this->requestMethod = $method;
    }
}
