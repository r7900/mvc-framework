<?php

const DB_HOST = "localhost";
const DB_NAME = "test_blog";
const DB_USER = "root";
const DB_PASS = "";

const SITE_URL = '';

/// set DEV_ENV to 0 in production environment , it will log errors instead of showing them publicly
const DEV_ENV = 1;

define("APP_ROOT", dirname(__FILE__));
define("ROOT", dirname(__FILE__, 2));



error_reporting(E_ALL);
ini_set('display_errors', DEV_ENV);
ini_set('log_errors', !DEV_ENV);
ini_set('error_log', ROOT . '/logs/errors.log');
