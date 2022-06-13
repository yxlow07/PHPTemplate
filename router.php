<?php

use app\router\Router;
use main\models\LoginModel;
use main\models\RegisterModel;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/app/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa");

// GET routes
$router->GET("/", "home", ["wrap"]);
$router->GET("/register", "register", ["wrap"]);
$router->GET("/login", "login", ["wrap"]);
$router->GET("/logout", "logout", ["wrap"]);

// POST routes
$router->POST("/register", function () {
    $default_values = require_once "schema/register_defaults.php";
    new RegisterModel($_POST, "ProjectPapa", "users", $default_values);
});
$router->POST("/login", function () {
    new LoginModel($_POST, "ProjectPapa", "users");
});

$router->run();