<?php

require_once "../app/config.php";
require ROOT . '/vendor/autoload.php';
require_once "Router.php";

/*
spl_autoload_register(function ($classname) {
    //echo $classname.'<br>';
    //echo APP_ROOT.'/'.$classname.'.php'."<br>";
    include str_replace('\\', '/', '../' . $classname . '.php');
});
*/
require_once "../app/route.php";

//echo "<pre>";
//print_r($_GET);
//echo "</pre>";
