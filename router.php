<?php

use app\router\Router;
use Dotenv\Dotenv;
use main\controllers\LoginController;
use main\controllers\ProfileController;
use main\controllers\RegisterController;

$dir = __DIR__ . "\\";

require_once $dir . "/vendor/autoload.php";
require_once $dir . "/app/config.php";

$dotenv = Dotenv::createImmutable($dir);
$dotenv->load();
$router = new Router($_ENV["ROOT"], $dir);

$router->GET("/", "home");
$router->GET("/home", "home");
$router->GET("/register", "register", /*["layout" => "auth"]*/);
$router->GET("/login", "login", /*["layout" => "auth"]*/);
$router->GET("/logout", [LoginController::class, "logout"]);
$router->GET("/profile", [ProfileController::class, "get"]);
$router->GET("/edit_profile", [ProfileController::class, "edit"]);

$router->POST("/register", function () {
    $regController = new RegisterController(__DIR__);
    $regController->setDb($_SERVER["DB_NAME"], $_ENV["USER_TABLE"]);
    $regController->run((array)json_decode($_POST["register"]) ?? []);
});

$router->POST("/login", function () {
    $loginController = new LoginController();
    $loginController->setDb($_SERVER["DB_NAME"], $_ENV["USER_TABLE"]);
    $loginController->run((array)json_decode($_POST["login"]) ?? []);
});

$router->POST("/edit_profile", [ProfileController::class, "run"]);
$router->POST("/upload/pfp", [ProfileController::class, "updatePfp"]);
$router->run();