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
</head>
<body>
<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container-fluid text-light">
        <a class="navbar-brand" href="{home}/">
            <img src="{home}/static/images/logo.png" width="30" height="30"
                 class="me-2 d-inline-block align-top rounded-circle" alt="">
            PROJECT PAPA
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            </ul>
            <div class="d-flex">
                <a href="{home}/register">
                    <button class="btn m-sm-1 btn-outline-light">Register <i class="fa-solid fa-address-card"></i>
                    </button>
                </a>
                <a href="{home}/login">
                    <button class="btn m-sm-1 btn-outline-light">Login <i class="fa-solid fa-right-to-bracket"></i>
                    </button>
                </a>
                <a href="{home}/profile">
                    <button class="btn m-sm-1 btn-outline-light">Profile <i class="fa-solid fa-circle-user"></i>
                    </button>
                </a>
                <a href="{home}/logout">
                    <button class="btn m-sm-1 btn-outline-light">Logout <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="vertical-center horizontal-center">
    <div class="container py-4">
        {{content}}
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>