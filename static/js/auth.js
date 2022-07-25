let template = "<div class=\"alert alert-danger d-flex align-items-center justify-content-center form-w500\" role=\"alert\"><div class='d-block'>{messages}</div></div>";
let msgTemplate = "<div class='text-center'>{msg}</div>";
let messages = [];

function createError(errors) {
    let err_str = "";
    console.error("Displaying errors")
    errors.forEach(message => {
        messages.push(msgTemplate.replace("{msg}", message));
    })
    messages.forEach(msg => {
        err_str += msg;
    })
    err_str = template.replace("{messages}", err_str);
    $("#errors").append(err_str);
}

function success_msg(successMsg = "Success") {
    let banner = `<div class="alert alert-success d-flex align-items-center justify-content-center form-w500" role="alert"><div class="d-block"><div class="text-center">${successMsg}</div></div></div>`;
    let errs = $("#errors");
    errs.empty()
    errs.append(banner)
}

function parseStatus(statusAndMsg, successMsg) {
    let status = statusAndMsg.status ?? false;
    if (!status) {
        createError([statusAndMsg.message ?? statusAndMsg.msg])
    } else {
        success_msg(successMsg)
        setTimeout(() => {
            location.href = "./"
        }, 1000)
    }
}

async function sendData(formData, name = "login", fetchLocation = "./login", method = "POST", successMsg = "Login success... Redirecting...") {
    let fD = new FormData();

    fD.append(name, JSON.stringify(formData));

    const ctrl = new AbortController()    // timeout
    setTimeout(() => ctrl.abort(), 5000);

    try {
        let r = await fetch(fetchLocation, {method: method, body: fD, signal: ctrl.signal});

        if (r.status !== 200) {
            createError(["Endpoint not found"]);
        } else {
            parseStatus(await r.json(), successMsg)
        }
    } catch (e) {
        createError(["Unable to contact server"]);
        console.warn(e)
    }
}

$("#login").on("submit", function (e) {
    e.preventDefault();

    // Disable button
    disableButton($('[type="submit"]'));

    $("#errors").empty();
    messages = []

    let formData = {"e_u": "", "pwd": "", "login": true};
    let data = $(this).serializeArray();
    let errors = [];

    data.forEach(input => {
        let name = input.name ?? "";
        let val = input.value ?? "";

        if (name in formData) {
            formData[name] = val
        }
    });

    if (formData.e_u === "") {
        errors.push("Email / Username cannot be empty");
    }
    if (formData.pwd === "") {
        errors.push("Password cannot be empty");
    }

    if (errors.length !== 0) {
        createError(errors);
    } else {
        let res = sendData(formData);
    }
})

function disableButton(button) {
    button.attr('disabled', 'disabled');
    button.css('opacity', 0.5);
    button.text("Please wait a second...");
}

$("#resetPassword").on("submit", function (e) {
    e.preventDefault();

    // Disable button
    disableButton($('[type="submit"]'));

    $("#errors").empty();
    messages = []

    let formData = {"e_u": "", "resetPass": true};
    let data = $(this).serializeArray();
    let errors = [];
    let successMsg = "An email have been sent! Please check your email and follow instructions provided there."

    data.forEach(input => {
        let name = input.name ?? "";
        let val = input.value ?? "";

        if (name in formData) {
            formData[name] = val
        }
    });

    if (formData.e_u === "") {
        errors.push("Email / Username cannot be empty");
    }

    if (errors.length !== 0) {
        createError(errors);
    } else {
        let res = sendData(formData, "resetPassword", "./reset_password", "POST", successMsg);
    }
})

$("#resetPassword_").on("submit", function (e) {
    e.preventDefault();

    // Disable button
    disableButton($('[type="submit"]'));

    $("#errors").empty();
    messages = []

    let formData = {"pass": "", "confPass": "", "token": "", "resetPass": true};
    let data = $(this).serializeArray();
    let errors = [];
    let successMsg = "Successfully reseted your password. Redirecting back to login page..."

    data.forEach(input => {
        let name = input.name ?? "";
        let val = input.value ?? "";

        if (name in formData) {
            formData[name] = val
        }
    });

    if (formData.pass === "") {
        errors.push("Password cannot be empty");
    } else if (formData.confPass === "") {
        errors.push("Please confirm your password");
    }

    if (formData.token === "") {
        errors.push("Your token is not valid, please retry the link from your email or if this still doesn't work please request a new one.");
    }

    if (errors.length !== 0) {
        createError(errors);
    } else {
        let res = sendData(formData, "resetPassword", "./resetPassword", "POST", successMsg);
    }
})

$("#reg").on("submit", function (e) {
    e.preventDefault();

    // Disable button
    disableButton($('[type="submit"]'));

    $("#errors").empty();
    messages = []

    let formData = {"email": "", "username": "", "pwd": "", "pwdConf": "", "reg": true};
    let data = $(this).serializeArray();
    let errors = [];
    let successMsg = "Register success... Redirecting...";

    data.forEach(input => {
        let name = input.name ?? "";
        let val = input.value ?? "";

        if (name in formData) {
            formData[name] = val
        }
    });

    if (formData.email === "") {
        errors.push("Email cannot be empty");
    }
    if (formData.username === "") {
        errors.push("Username cannot be empty");
    }
    if (formData.pwd === "") {
        errors.push("Password cannot be empty");
    }
    if (formData.pwdConf === "") {
        errors.push("Please confirm your password");
    }

    if (errors.length !== 0) {
        createError(errors);
    } else {
        let res = sendData(formData, "register", "./register", "POST", successMsg);
    }
})

$('[type="submit"]').click(function () {

})