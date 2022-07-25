<div class="row d-flex justify-content-center" id="errors">
    {{err}}{{msg}}
</div>
<div class="text-center mb-5 ms-4">
    <h1 class="fw-bolder">Reset Your Password</h1>
</div>
<div class="row">
    <div class="col"></div>
    <div class="col">
        <form action="reset_password" method="post" id="resetPassword">
            <div class="mb-3">
                <label for="e_u" class="form-label">Email address / Username:</label>
                <input type="text" class="form-control" name="e_u" id="email_or_username">
            </div>
            <input type="hidden" name="reset_pass">
            <div class="row horizontal-center">
                <button type="submit" class="btn btn-dark w-auto">Send me an email</button>
            </div>
        </form>
    </div>
    <div class="col"></div>
</div>
<script>document.title = "Reset Password"</script>

<?php

use app\views\Widgets;

Widgets::js_script("{home}/static/js/auth.js");
