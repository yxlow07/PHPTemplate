<?php

use Verification\Verification;
include_once "assets/php/config.php";

$router = new Router(__DIR__ . "\\", "/ProjectPapa", "http://localhost/ProjectPapa",);

$router->GET("/", "index", ["wrap"]);
$router->GET("/register", "register", ["wrap"]);
$router->POST("/register", function () {
    $errors = [];
    $verification = new Verification();
    if (isset($_POST['register'])) {

        $email_validation = $verification->validate($_POST['email'] ?? false, "Email", ["notEmpty", ["length"], "email"]);
        if ($email_validation !== true) {
            $errors["email_err"] = $email_validation;
        }

        $username_validation = $verification->validate($_POST['username'] ?? false, "Username", ["notEmpty", ["length", 5, 30]]);
        if ($username_validation !== true) {
            $errors["username_errors"] = $username_validation;
        }

        $pwd_validation = $verification->validate($_POST['pwd'] ?? false, "Password", [ "notEmpty", ["length"], ["match", "confirmed password", $_POST['pwdConf']] ]);
        if ($pwd_validation !== true) {
            $errors["pwd_errors"] = ($pwd_validation);
        }
    }
    echo "<pre>";
    print_r($errors);
    echo "</pre>";
});

$router->run();