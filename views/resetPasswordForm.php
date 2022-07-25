<div class="row d-flex justify-content-center" id="errors">
    {{err}}{{msg}}
</div>
<div class="text-center mb-5 ms-4">
    <h1 class="fw-bolder">Reset Your Password</h1>
</div>
<div class="row">
    <div class="col"></div>
    <div class="col">
        <form action="resetPassword" method="post" id="resetPassword_">
            <div class="mb-3">
                <label for="pass" class="form-label">Password: </label>
                <input type="password" class="form-control" name="pass">
            </div>
            <div class="mb-3">
                <label for="confPass" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" name="confPass">
            </div>
            <input type="hidden" name="token" value="<?= $_GET['reset_id'] ?? '' ?>">
            <div class="row horizontal-center">
                <button type="submit" class="btn btn-dark w-auto">Don't forget it again</button>
            </div>
        </form>
    </div>
    <div class="col"></div>
</div>
<script>document.title = "Reset Password"</script>

<?php

use app\views\Widgets;

Widgets::js_script("{home}/static/js/auth.js");
