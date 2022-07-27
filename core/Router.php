<?php

namespace Core;

use Core\Route;

class Router
{

    private static $routes;
    private static $params;
    private static $url;

    private static function route(string $method, string &$routeUrl, mixed &$target)
    {
        $file = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['file'];
        $file = explode('/', $file);
        $file = rtrim(array_pop($file), '.php');

        $route = new Route($method, $routeUrl, $target, $file === 'api');
        self::$routes[] = $route;

        return $route;
    }
    /**
     *  @param string $method Request method
     *  @param string $routeUrl
     *  @param mixed $target 
     * string: 2 strings seperated with '@' i.e "ControllerClass@targetMethod" for routing to a method inside a controller. or 1 string without '@' i.e "viewFile" for routing directly to view
     * 
     * Closure: An anonymous function that will call instead of calling controller .
     */
    public static function add(string $method, string $routeUrl, mixed $target)
    {
        return self::route($method, $routeUrl, $target);
    }

    public static function get(string $routeUrl, mixed $target)
    {
        return self::route('GET', $routeUrl, $target);
    }
    public static function post(string $routeUrl, mixed $target)
    {
        return self::route('POST', $routeUrl, $target);
    }
    public static function put(string $routeUrl, mixed $target)
    {
        return self::route('PUT', $routeUrl, $target);
    }
    public static function delete(string $routeUrl, mixed $target)
    {
        return self::route('DELETE', $routeUrl, $target);
    }
    public static function patch(string $routeUrl, mixed $target)
    {
        return self::route('PATCH', $routeUrl, $target);
    }

    private static function match()
    {
        self::$url = trim($_GET['url'] ?? '', '/');
        self::$url = $_GET['url'] ?? '/';
        foreach (self::$routes as &$route) {
            $regex = str_replace('/', '\/', $route->url);
            $regex = preg_replace('/{(.+?)}/', '(?<${1}>[^\/]+)', $regex);
            $regex = '/^' . $regex . '\/??$/';
            if (preg_match($regex, self::$url, $matchs)) {
                if ($_SERVER['REQUEST_METHOD'] !== $route->method) {
                    continue;
                }
                foreach ($matchs as $key => &$match) {
                    //echo $key."<br>";
                    if (is_string($key)) {
                        self::$params[$key] = $match;
                    }
                }
                return $route;
            }
        }
        return 0;
    }
    public static function dispatch()
    {
        $matchedRoute = self::match();
        if ($matchedRoute) {
            self::handleBeforeMiddlewares($matchedRoute);
            if ($matchedRoute->target instanceof \Closure) {
                if (Controller::isPost($matchedRoute->method)) {
                    throw new \RuntimeException("POST requests need controller !");
                }
                ($matchedRoute->target)();
                self::handleAfterMiddlewares($matchedRoute);
                return;
            }
            $pos = strpos($matchedRoute->target, '@');

            if (!$pos) {
                if (Controller::isPost($matchedRoute->method)) {
                    throw new \RuntimeException("POST requests need controller !");
                }
                \Core\View::render($matchedRoute->target);
            } else {
                $controller = substr($matchedRoute->target, 0, $pos);
                $method = substr($matchedRoute->target, $pos + 1);
                $classname = "App\\Controllers\\" . $controller;

                if (!class_exists($classname)) {
                    throw new \RuntimeException('Controller class "' . $controller . "\" doesn't exist !!!");
                }
                $controllerClass = new $classname(self::$params, $matchedRoute->method);

                if (!method_exists($controllerClass, $method)) {
                    throw new \RuntimeException("'" . $method . "' method in '" . $controller . "' Controller class " . " doesn't exist !!!");
                }
                call_user_func([$controllerClass, $method]);
            }
            self::handleAfterMiddlewares($matchedRoute);
        } else {
            \Core\View::error404();
        }
    }

    private static function handleBeforeMiddlewares(Route &$route)
    {
        foreach (Middleware::$globalMiddlewares as $gm) {
            if (!class_exists($gm)) {
                throw new \RuntimeException("global middleware '" . $gm . "' doesn't exist  in '/App/middlewares/global/' !!!");
            }
            call_user_func([$gm, 'before']);
        }
        foreach ($route->getMiddlewares() as $m) {
            if (!class_exists($m)) {
                throw new \RuntimeException("route middleware '" . $m . "' doesn't exist  in '/App/middlewares/route/' !!!");
            }
            call_user_func([$m, 'before']);
        }
    }

    private static function handleAfterMiddlewares(Route &$route)
    {
        foreach (Middleware::$globalMiddlewares as $gm) {
            if (!class_exists($gm)) {
                throw new \RuntimeException("global middleware '" . $gm . "' doesn't exist  in '/App/middlewares/global/' !!!");
            }
            call_user_func([$gm, 'after']);
        }
        foreach ($route->getMiddlewares() as $m) {
            if (!class_exists($m)) {
                throw new \RuntimeException("route middleware '" . $m . "' doesn't exist  in '/App/middlewares/global/' !!!");
            }
            call_user_func([$m, 'after']);
        }
    }

    public static function printRoutes()
    {
        foreach (self::$routes as &$route) {
            echo "<pre>";
            echo 'route url: <strong>' . ($route->url === '' ? '/' : $route->url) . '</strong><br>';
            echo 'route name: <strong>' . $route->name . '</strong><br>';
            echo 'request method: ' . $route->method . '<br>';
            echo 'route target: ' . ($route->target instanceof \Closure ? 'closure' : $route->target) . '<br>';
            echo 'route middlewares: ' . implode(',', $route->getMiddlewares()) . '<br>';
            echo 'route params: ';
            print_r(self::$params);
            echo '<br>';
            echo "</pre>";
        }
    }

    public static function getRoutes()
    {
        return self::$routes;
    }


    /**
     * @param string $routName name that given in add method to route
     * @return string|bool return route url if route has name otherwise 0 
     */
    public static function getRouteName(string $name)
    {
        foreach (self::$routes  as &$route) {
            if ($route->name === $name) {
                return $route->url === '' ? '/' : $route->url;
            }
        }
        return 0;
    }
};
