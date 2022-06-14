<?php

use app\router\Router;
use Dotenv\Dotenv;
use main\controllers\LoginController;
use main\controllers\RegisterController;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/app/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa");
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router->GET("/", "home");
$router->GET("/home", "home");
$router->GET("/register", "register");
$router->GET("/login", "login");
$router->GET("/logout", [main\controllers\LoginController::class, "logout"]);

$router->POST("/register", function () {
    $regController = new RegisterController();
    $regController->setDefaultValues(__DIR__, "register_defaults.php");
    $regController->setDb($_ENV["DB_NAME"], $_ENV["USER_TABLE"]);
    $regController->run($_POST);
});
$router->POST("/login", function () {
    $loginController = new LoginController();
    $loginController->setDb($_ENV["DB_NAME"], $_ENV["USER_TABLE"]);
    $loginController->run($_POST);
});

$router->run();