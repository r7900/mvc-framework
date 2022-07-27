<?php

use Core\Router;



Router::add('GET', '/', "HomeController@index")->name('home_page');

Router::get('/test', function () {
    echo 'test';
});
