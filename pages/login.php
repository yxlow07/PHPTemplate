<?php include_once "assets/php/config.php" ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <form action="login" method="post">
        <label>Email / Username: </label> <input type="email" name="email" maxlength="256">
        <label>Password: </label> <input type="password" name="password">
        <button>Login</button>
    </form>
</body>
</html>