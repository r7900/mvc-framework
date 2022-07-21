<?php

namespace Core;

use Core\Db;

class Model
{
    public function __construct()
    {
        Db::connect();
    }
}
