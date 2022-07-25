<?php

namespace Core;

class View
{
    /**
     * 
     * @var array $viewData
     */
    private static $viewData = [];

    /**
     * Stores additional data before calling render
     * 
     */
    public static function setData($arr)
    {
        self::$viewData = $arr;
    }

    /**
     * @var string $viewPath 
     * Relative path of view file in app/views/
     * 
     * @var array $data
     * An array of variable to send into view file . Variable inside this array will override duplicate variables from setData method if its already called ! 
     * 
     */
    public static function render(string $viewPath, array $data = [])
    {
        $viewPath = rtrim($viewPath, '.php');
        $viewPath = ltrim($viewPath, '/');

        $path = APP_ROOT . '/views/' . $viewPath . '.php';
        if (file_exists($path)) {
            // ob_start();
            extract(self::$viewData);
            extract($data);
            require_once($path);
            // ob_get_flush();
            exit();
        } else {
            throw new \RuntimeException('View "' . $viewPath . '.php" does not exist !!!');
        }
    }

    /**
     * Unauthorized request error
     */
    public static function error401()
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized', true, 401);
        self::render('errors/401');
    }

    /**
     * Forbidden request error
     */
    public static function error403()
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
        self::render('errors/403');
    }

    /**
     * Page not found error
     */
    public static function error404()
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Page not found', true, 404);
        self::render('/errors/404');
    }
    public static function customError($statusCode)
    {
        header($_SERVER['SERVER_PROTOCOL'] . $statusCode, true, $statusCode);
        self::render('/errors/' . $statusCode);
    }
}
