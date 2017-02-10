<?php

define('BASE_URL', $_SERVER['PHP_SELF']);
define('CURRENT_URL', 'http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

spl_autoload_register(function ($class) {
    $class = __DIR__ . '/../classes/' . $class . '.php';
    require $class;
});

$db = new Db;
$validator = new Validator;
$sanitizer = new Sanitizer;
$paginator = new Paginator;
