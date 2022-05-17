<?php

use Verification\Verification;

include_once "assets/php/config.php";
include_once "assets/php/Classes/Verification.php";

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

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="assets/images/logo2.png" type="image/x-icon">
    <script src="assets/js/icons.js"></script>
    <title>Register</title>
</head>
<body>
<pre><?php print_r($errors) ?></pre>
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