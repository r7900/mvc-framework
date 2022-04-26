<?php

require_once "../app/config.php";
require_once "Router.php";


spl_autoload_register(function ($classname) {
    //echo $classname.'<br>';
    //echo APP_ROOT.'/'.$classname.'.php'."<br>";
    include str_replace('\\', '/', '../' . $classname . '.php');
});

use Core\Router;



Router::add('GET', '/', "HomeController@index", 'Home');
Router::add('GET', 'user/{id}', "User@Login");

//Router::printRoutes();

Router::dispatch();



//echo "<pre>";
//print_r($_GET);
//echo "</pre>";
