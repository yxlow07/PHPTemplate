<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PROJECT PAPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <script src="{home}/static/js/icons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="{home}/static/css/main.css">
    <script src="{home}/static/js/mixitup.js"></script>
    <link rel="stylesheet" href="{home}/static/css/shop.css?id=<?= bin2hex(random_bytes(10)) ?>">
</head>
<body>
<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container-fluid text-light">
        <a class="navbar-brand link-nav" href="{home}/">
            PROJECT <br/> PAPA
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            </ul>
            <div class="d-flex">
                <?php if (isset($_SESSION['_id'])) { ?>
                    <a class="link-nav" href="{home}/profile">PROFILE</a>
                    <a class="link-nav" href="{home}/shop">SHOP</a>
                    <a class="link-nav" href="{home}/logout">LOGOUT</a>
                <?php } else { ?>
                    <a class="link-nav" href="{home}/login">LOGIN</a>
                    <a class="link-nav" href="{home}/login">REGISTER</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>
<div class="vertical-center horizontal-center">
    <div class="container">
        {{content}}
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>