<?php

use Verification\Verification;

include_once "assets/php/config.php";
include_once "assets/php/Classes/Verification.php";

if (isset($_POST['register'])) {
    $verification = new Verification();
    $errors = [];

    if ( $email_validation = $verification->validate($_POST['email'] ?? false, ["notEmpty", "length", "email"]) ) {
        $eml_s = $verification->returnErrorMessage($email_validation, "Email");

        if ($eml_s === true) {
            echo "Email passed";
        } else {
            $errors["email_errors"] = $eml_s;
        }
    }

    if ( $username_validation = $verification->validate($_POST['username'] ?? false, ["notEmpty", "length"]) ) {
        $usr_s = $verification->returnErrorMessage($username_validation, "Username");

        if ($usr_s === true) {
            echo "Username passed";
        } else {
            $errors["username_errors"] = $usr_s;
        }
    }

    if ( $pwd_validation = $verification->validate($_POST['pwd'] ?? false, ["notEmpty", "length"]) ) {
        $pwd_s = $verification->returnErrorMessage($pwd_validation, "Password");

        if ($pwd_s === true) {
            echo "Password passed";
        } else {
            $errors["pwd_errors"] = ($pwd_s);
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="./assets/images/logo2.png" type="image/x-icon">
    <script src="./assets/js/icons.js"></script>
    <title>Register</title>
</head>
<body>
    <form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
        <label>Email: <input type="email" name="email"> </label> <br>
        <label>Username: <input type="text" name="username"> </label> <br>
        <label>Password:  <input type="password" name="pwd"> </label> <br>
        <label>Confirm Password:  <input type="password" name="pwdConf"> </label> <br>
        <input type="hidden" name="register">
        <button type="submit">Register</button>
    </form>
</body>
</html>