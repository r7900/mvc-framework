<?php

namespace Core;

class View
{
    public static function render($viewPath, $data = [])
    {
        $path = APP_ROOT . "/Views/" . $viewPath . '.php';
        if (file_exists($path)) {
            require_once($path);
            exit();
        } else {
            throw new \RuntimeException('View "' . $viewPath . '.php" does not exist !!!');
        }
    }
    public static function NotFound404()
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Page not found', true, 404);
        self::render('404');
    }
}
