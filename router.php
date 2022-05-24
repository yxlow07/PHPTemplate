<?php
include_once "assets/php/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa",);

$router->GET("/", "index", ["wrap"]);
$router->GET("/movie/{id}", "index", ["wrap"]);
$router->GET("/register", "register", ["wrap"]);
$router->GET("/hello world", "index");

$router->run();