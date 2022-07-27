<?php

// api routes will have a 'api/' prefix

use Core\Router;

Router::get('post', function () {
    echo 'post';
});

// Router::printRoutes();