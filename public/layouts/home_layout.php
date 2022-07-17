<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>ProjectPapa</title>
    <link href="{home}/static/css/home.css" rel="stylesheet" type="text/css">
    <script src="{home}/static/js/mixitup.js"></script>
    <link rel="stylesheet" href="{home}/static/css/shop.css">
    <!-- Styles -->
    <style>
        .banner {
            background-image: url("{home}/static/images/main/banner_compressed.jpg");
        }
    </style>
</head>
<body>
<!-- Main Container -->
<div class="container py-4">
    <!-- Navigation -->
    <header>
        <h5 class="logo"><a href="{home}">PROJECT PAPA</a></h5>
        <a href=""> </a>
        <nav>
            <ul>
                <?php if (isset($_SESSION['_id'])) { ?>
                    <li><a href="{home}/profile">PROFILE</a></li>
                    <li><a href="{home}/shop">SHOP</a></li>
                    <li><a href="{home}/logout">LOGOUT</a></li>
                <?php } else { ?>
                    <li><a href="{home}/login">LOGIN</a></li>
                    <li><a href="{home}/login">REGISTER</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    {{content}}
</div>
<!-- Main Container Ends -->
</body>
</html>
