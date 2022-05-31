<?php

use Verification\Verification;
include_once "assets/php/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa",);

$router->GET("/", "index", ["wrap"]);
$router->GET("/register", "register", ["wrap"]);
$router->POST("/register", function () {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "This is the post request";
});

$router->run();