<?php
use auth\Login;
use auth\Register;

include_once "app/php/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa");

// GET routes
$router->GET("/", "index", ["wrap"]);
$router->GET("/register", "register", ["wrap"]);
$router->GET("/login", "login", ["wrap"]);
$router->GET("/logout", "logout", ["wrap"]);

// POST routes
$router->POST("/register", function () {
    $default_values = require_once "schema/register_defaults.php";
    new Register($_POST, "ProjectPapa", "users", $default_values);
});
$router->POST("/login", function () {
    new Login($_POST, "ProjectPapa", "users");
});

$router->run();