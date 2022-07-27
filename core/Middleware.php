<?php

namespace Core;

abstract class Middleware
{
    /**
     * global middlewares will run for all requests that match with a route
     */
    public static $globalMiddlewares = [
        \App\Middlewares\Global\TestMiddleware::class,
    ];

    abstract protected static function before();
    abstract protected static function after();
}
