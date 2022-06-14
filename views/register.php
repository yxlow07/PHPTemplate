<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{home}/assets/images/logo2.png" type="image/x-icon">
    <script src="{home}/assets/js/icons.js"></script>
    <title>Register</title>
</head>
<body>
<fieldset>
    <legend>Register</legend>
    <form action="register" method="POST">
        <label>Email: <input type="text" name="email"> </label> <br>
        <label>Username: <input type="text" name="username"> </label> <br>
        <label>Password: <input type="password" name="pwd"> </label> <br>
        <label>Confirm Password: <input type="password" name="pwdConf"> </label> <br>
        <input type="hidden" name="reg" value="hello">
        <button type="submit">Register</button>
    </form>
</fieldset>
</body>
</html>