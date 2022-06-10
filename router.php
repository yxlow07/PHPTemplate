<?php
use auth\Login;
use auth\Register;

include_once "assets/php/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa");

// GET routes
$router->GET("/", "index", ["wrap"]);
$router->GET("/register", "register", ["wrap"]);
$router->GET("/login", "login", ["wrap"]);

// POST routes
$router->POST("/register", function () {
    $default_values = [
        "role" => "user",
        "status" => "offline",
        "date_created" => date("D M Y H:i:s A"),
        "date_modified" => date("D M Y H:i:s A"),
        "user_info" => [
            "name" => "",
            "profile_img" => "",
            "bio" => "",
            "twoFA" => false,
        ]
    ];
    new Register($_POST, "ProjectPapa", "users", $default_values);
});
$router->POST("/login", function () {
    new Login($_POST, "ProjectPapa", "users");
});

$router->run();