<?php
include_once "assets/php/config.php";

function pwdConf($password)
{
    if ($pwdConfVerf = Verification::validate($_POST['pwdConf'] ?? false, [0, 1])) {
        if (Verification::checkReturns($pwdConfVerf)) {
            if ($password === $_POST['pwdConf'])
                echo "Password all passed";
            else
                echo "Passwords are not the same";
        } else {
            Verification::returnErrorMessage(["issetAndNotEmpty", "length" => "256"], $pwdConfVerf, "Confirmed password");
        }
    }
}

if (isset($_POST['register'])) {
    if ($email_validation = Verification::validate($_POST['email'] ?? false, [0, 1, "email"])) {
        if (Verification::checkReturns($email_validation)) {
            echo "Email passed";
        } else {
            Verification::returnErrorMessage(["issetAndNotEmpty", "length" => "256", "email"], $email_validation, "Email");
        }
    }
    if ($username_validation = Verification::validate($_POST['username'] ?? false, [0, 1])) {
        if (Verification::checkReturns($username_validation)) {
            echo "Username passed";
        } else {
            Verification::returnErrorMessage(["issetAndNotEmpty", "length" => "256"], $username_validation, "Username");
        }
    }
    if ($pwd_validation = Verification::validate($_POST['pwd'] ?? false, [0, 1])) {
        if (Verification::checkReturns($pwd_validation)) {
            pwdConf($_POST['pwd']);
        } else {
            Verification::returnErrorMessage(["issetAndNotEmpty", "length" => "256"], $pwd_validation, "Password");
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
    <link rel="shortcut icon" href="./assets/images/2.png" type="image/x-icon">
    <script src="./assets/js/icons.js"></script>
    <title>Register</title>
</head>

<body>
    <i class="fa-duotone fa-360-degrees"></i>
    <form action="<?php //htmlspecialchars($_SERVER['PHP_SELF'])
                    ?>" method="post">
        <label>Email:</label> <input type="email" name="email"> <br>
        <label>Username:</label> <input type="text" name="username"> <br>
        <label>Password: </label> <input type="password" name="pwd"> <br>
        <label>Confirm Password: </label> <input type="password" name="pwdConf"> <br>
        <input type="hidden" name="register">
        <button type="submit">Register</button>
    </form>
</body>

</html>