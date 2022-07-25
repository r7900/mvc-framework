<?php

use Core\Router;



$route = Router::add('GET', '/', "HomeController@index")->name('home_page');

Router::get('/test', function () {
    echo 'test';
});

// Router::printRoutes();
