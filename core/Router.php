<?php

namespace Core;

require_once "View.php";
require_once "Controller.php";
//require_once "../app/controllers/HomeController.php";

//use Core\View;

class Router
{

    private static $routes;
    private static $params;
    private static $url;

    /**
     *  @param string $method GET,POST 
     *  @param string $rout
     *  @param string $params 2 strings seperated with '@' i.e "ControllerClass@targetMethod" for routing to a method inside a controller. or 1 string without '@' i.e "viewFile" for routing directly to view
     *  @param string $routeName name of the route [optional]
     */
    public static function add($method, $rout, $target, $routeName = '')
    {
        $route = trim($rout, '/');
        self::$routes[$rout] = [
            'reqMethod' => $method,
            'target' => $target,
            'name' => $routeName
        ];
    }
    public static function match()
    {
        self::$url = trim($_GET['url'] ?? '', '/');
        self::$url = $_GET['url'] ?? '/';
        foreach (self::$routes  as $routeUrl => &$route) {
            $regex = str_replace('/', '\/', $routeUrl);
            $regex = preg_replace('/{(.+?)}/', '(?<${1}>[^\/]+)', $regex);
            $regex = '/^' . $regex . '\/??$/';
            if (preg_match($regex, self::$url, $matchs)) {
                if ($_SERVER['REQUEST_METHOD'] != $route['reqMethod']) {
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
        $matchedRout = self::match();
        if ($matchedRout) {
            $pos = strpos($matchedRout['target'], '@');
            if (!$pos) {
                \Core\View::render($matchedRout['target']);
            } else {
                $controller = substr($matchedRout['target'], 0, $pos);
                $method = substr($matchedRout['target'], $pos + 1);
                //echo 'Controller: ' . $controller . ", Method: " . $method . '<br>';
                $classname = "App\\Controllers\\" . $controller;
                //$classname = str_replace('/','\\',"App\\Controllers\\".$controller);
                if (!class_exists($classname)) {
                    throw new \RuntimeException('Controller class "' . $controller . "\" doesn't exist !!!");
                }
                $controllerClass = new $classname(self::$params, $matchedRout['reqMethod']);
                if (!method_exists($controllerClass, $method)) {
                    throw new \RuntimeException("Method " . $method . " in Controller class " . $controller . " not exist !!!");
                }
                call_user_func([$controllerClass, $method]);
            }
        } else {
            \Core\View::NotFound404();
        }
    }


    public static function printRoutes()
    {
        foreach (self::$routes  as $routName => &$rout) {
            echo "<pre>";
            //echo 'Number :' . $key . '<br>';
            echo 'route name: <strong>' . $routName . '</strong><br>';
            //echo 'route Controller :' . $rout['Controller'] . '<br>';
            echo 'request method: ' . $rout['reqMethod'] . '<br>';
            echo 'route target: ' . $rout['target'] . '<br>';
            echo 'route params: ';
            print_r(self::$params);
            echo '<br>';
            echo "</pre>";
        }
    }

    public static function getroutes()
    {
        return self::$routes;
    }


    /**
     * @param string $routName name that given in add method to route
     * @return string|bool return route url if route has name otherwise 0 
     */
    public static function getRout($routName)
    {
        foreach (self::$routes  as $routeUrl => &$route) {
            if ($route['name'] == $routName) {
                return $routeUrl;
            }
        }
        return 0;
    }
    private function __construct()
    {
        echo "consructor ran";
        $routes = [];
    }
    /*
    public static function getRouter()
    {
        if (!static::$router){
            static::$router = new static;
            //echo 'router initialized '.'<br>';
        }else
            //echo 'router was initialized '.'<br>';

        return static::$router;
    }
    */
};
