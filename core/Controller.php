<?php

namespace Core;

use Core\View;
use Core\Session;

abstract class Controller
{
    protected $parameters;
    protected $requestMethod;
    public function __construct($param, $method)
    {
        $this->parameters = $param;
        $this->requestMethod = $method;


        if ($method == 'POST') {
            session_start();
            if (!self::auth_csrf()) {
                exit('invalid request');
            }
        }
    }
    private static function auth_csrf()
    {
        if (!Session::has('_token') || !isset($_POST['_token'])) {
            return false;
        }
        if (time() > Session::get('token_expire') || Session::get('_token') !== $_POST['_token']) {
            return false;
        }
        return true;
    }


    protected function csrfToken($expireTime = 5 * 60)
    {
        $token = bin2hex(random_bytes(32));
        Session::set('_token', $token);
        Session::set('token_expire', time() + $expireTime);
        return $token;
    }
    protected function csrfInput($expireTime = 5 * 60)
    {
        $token = bin2hex(random_bytes(32));
        Session::set('_token', $token);
        Session::set('token_expire', time() + $expireTime);
        return "<input type='hidden' name='_token' value='" . $token . "'>";
    }
}
