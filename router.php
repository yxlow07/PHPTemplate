<?php
include_once "assets/php/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa");

$router->GET("/", function () {
    echo "<pre>";
    print_r($_SERVER);
});

$router->run();