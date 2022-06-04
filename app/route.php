<?php

use Core\Router;



Router::add('GET', '/', "HomeController@index", 'Home');
Router::add('POST', '/', "HomeController@test", 'Home');


// Router::printRoutes();

Router::add('GET', 'user/{id}', "User@Login");


Router::dispatch();
