<?php

use auth\Login;
use auth\Register;
use Verification\Verification;
include_once "assets/php/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa",);

// GET routes
$router->GET("/", "index", ["wrap"]);
$router->GET("/register", "register", ["wrap"]);
$router->GET("/login", "login", ["wrap"]);

// POST routes
$router->POST("/register", function () {
    new Register($_POST);
});
$router->POST("/login", function () {
    new Login($_POST);
});

$router->run();