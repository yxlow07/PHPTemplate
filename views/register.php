<div class="text-center">
    <h1 class="fw-bolder">Register</h1>
    <p class="form-text">Make your own account today</p>
</div>
<div class="row">
    <div class="col"></div>
    <div class="col">
        <form action="register" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" name="email" id="email">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username">
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" name="pwd" id="pwd">
            </div>
            <div class="mb-3">
                <label for="pwdConf" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" name="pwdConf" id="pwdConf">
            </div>
            <input type="hidden" name="reg">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <button type="submit" class="btn btn-dark w-auto">Register</button>
                </div>
                <div class="col"></div>
            </div>
        </form>
    </div>
    <div class="col"></div>
</div>
<script>document.title = "Register"</script>