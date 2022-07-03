<div class="row d-flex justify-content-center" id="errors">

</div>
<div class="text-center">
    <h1 class="fw-bolder">Login</h1>
    <p class="form-text">Sign in to continue</p>
</div>
<div class="row">
    <div class="col"></div>
    <div class="col">
        <form action="login" method="post" id="login">
            <div class="mb-3">
                <label for="email_or_username" class="form-label">Email address / Username:</label>
                <input type="text" class="form-control" name="e_u" id="email_or_username">
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" name="pwd" id="pwd">
            </div>
            <input type="hidden" name="login">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <button type="submit" class="btn btn-dark w-auto">Login</button>
                </div>
                <div class="col"></div>
            </div>
        </form>
    </div>
    <div class="col"></div>
</div>
<script>document.title = "Login"</script>
<?php

use app\views\Widgets;

Widgets::js_script("{home}/static/js/auth.js");