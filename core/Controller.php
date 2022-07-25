<?php

namespace Core;

use Core\Session;

abstract class Controller
{
    protected $parameters;
    protected $requestMethod;
    public function __construct($param, $method)
    {
        $this->parameters = $param;
        $this->requestMethod = $method;


        if (self::isPost($method)) {
            session_start();
            if (!self::authWebCsrf()) {
                \Core\View::error403();
            }
        }
    }

    /**
     * return true if method is post,put,delete,patch
     */
    public static function isPost(&$method): bool
    {
        if ($method === 'POST' || $method === 'PUT' || $method === 'DELETE' || $method === 'PATCH')
            return true;
        return false;
    }
    private static function authWebCsrf()
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
        // header('X-CSRF-TOKEN: ' . $token, 1);
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
