<?php

use app\router\Router;
use app\views\Views;
use Dotenv\Dotenv;
use main\controllers\LoginController;
use main\controllers\ProfileController;
use main\controllers\RegisterController;
use main\controllers\ShopController;

$dir = __DIR__ . "\\";

require_once $dir . "/vendor/autoload.php";
require_once $dir . "/app/config.php";

$dotenv = Dotenv::createImmutable($dir);
$dotenv->load();
$router = new Router($_ENV["ROOT"], $dir);

$router->GET("/", "home", ["layout" => "home_layout.php"]);
$router->GET("/register", "register");
$router->GET("/login", "login");
$router->GET("/reset_password", [LoginController::class, "resetPasswordGet"]);
$router->GET("/resetPassword", [LoginController::class, "findToken"]);
$router->GET("/logout", [LoginController::class, "logout"]);
$router->GET("/profile", [ProfileController::class, "get"]);
$router->GET("/edit_profile", [ProfileController::class, "edit"]);
$router->GET("/shop", [ShopController::class, "get"]);
$router->GET("/shop/books", [ShopController::class, "getBooks"]);

$router->POST("/register", function () {
    $regController = new RegisterController(__DIR__);
    $regController->setDb($_SERVER["DB_NAME"], $_ENV["USER_TABLE"]);
    $regController->run((array)json_decode($_POST["register"]) ?? []);
});

$router->POST("/login", function () {
    $loginController = new LoginController(new Views());
    $loginController->setDb($_SERVER["DB_NAME"], $_ENV["USER_TABLE"]);
    $loginController->run((array)json_decode($_POST["login"]) ?? []);
});

$router->POST("/edit_profile", [ProfileController::class, "run"]);
$router->POST("/upload/pfp", [ProfileController::class, "updatePfp"]);
$router->POST("/reset_password", [LoginController::class, "resetPasswordPost"]);
$router->POST("/resetPassword", [LoginController::class, "resetPasswordResetPost"]);
$router->run();