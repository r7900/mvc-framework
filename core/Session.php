<?php

namespace Core;

class  Session
{
    public static function set($name, $value)
    {
        if (!isset($_SESSION))
            session_start();
        if ($name !== '' && $value !== '') {
            return $_SESSION[$name] = $value;
        }
        throw new \Exception('name and value required !!!');
    }
    public static function get($name)
    {
        if (!isset($_SESSION[$name]))
            return 0;

        return $_SESSION[$name];
    }
    public static function has($name)
    {
        if ($name != '') {
            return isset($_SESSION[$name]);
        }
        throw new \Exception('name required !!!');
    }
    public static function remove($name)
    {
        if (self::has($name)) {
            unset($_SESSION[$name]);
        }
    }
}
