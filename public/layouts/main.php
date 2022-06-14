<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous">
    <script src="{home}/static/js/icons.js"></script>
    <link rel="stylesheet" href="{home}/static/css/main.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container-fluid text-light">
        <a class="navbar-brand" href="{home}/">
            <img src="{home}/static/images/logo2.png" width="30" height="30"
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
                    <button class="btn m-sm-1 btn-outline-light">Login <i class="fa-solid fa-user"></i></button>
                </a>
                <a href="{home}/logout">
                    <button class="btn m-sm-1 btn-outline-light">Logout <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="container py-4">
    {{content}}
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>
</html>