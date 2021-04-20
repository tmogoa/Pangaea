$(function () {
    const emailError = $("#email_error");
    const passwordError = $("#password_error");
    const passwordConfirmationError = $("#password_confirmation_error");
    const error = $("#error");
    $("form#registerForm").submit(function () {
        emailError.text("");
        passwordError.text("");
        passwordConfirmationError.text("");
        error.text("");

        if (matchPasswords()) {
            $.post(
                "logic/procedures/signup.php",
                $("form#registerForm").serialize(),
                function (data) {
                    console.log(data);
                    switch (data) {
                        case "UEE":
                            emailError.text("Bad email. Enter a valid one");
                            break;
                        case "PLSE":
                            passwordConfirmationError.text(
                                "Password should have 9 or more charcters"
                            );
                            passwordError.text(
                                "Password should have 9 or more charcters"
                            );
                            break;
                        case "PNE":
                            passwordConfirmationError.text(
                                "Password should include a number or more."
                            );
                            passwordError.text(
                                "Password should include a number or more."
                            );
                            break;
                        case "PLLE":
                            passwordConfirmationError.text(
                                "Password should one or more lowercase letters."
                            );
                            passwordError.text(
                                "Password should one or more lowercase letters."
                            );
                            break;
                        case "PULE":
                            passwordConfirmationError.text(
                                "Password should include one or more uppercase letters"
                            );
                            passwordError.text(
                                "Password should include one or more uppercase letters"
                            );
                            break;
                        case "NEE":
                            emailError.text("Email can't be empty.");
                            break;
                        case "NPE":
                            passwordConfirmationError.text(
                                "Password can't be empty."
                            );
                            passwordError.text("Password can't be empty.");
                            break;
                        case "EEE":
                            emailError.text(
                                "That email has already been taken."
                            );
                            break;
                        case "SQE":
                            error.text(
                                "We couldn't register you. Please try again later."
                            );
                            break;
                        case "OK":
                            document.location.href = "login.php";
                            break;
                    }
                }
            );
        } else {
            $("#password_error").text("Passwords do not match.");
            $("#password_confirmation_error").text("Passwords do not match.");
        }
        return false;
    });

    $("form#loginForm").submit(function (event) {
        emailError.text("");
        passwordError.text("");

        $.post(
            "logic/procedures/login.php",
            $("form#loginForm").serialize(),
            function (data) {
                switch (data) {
                    case "WEE":
                        emailError.text("We don't recognize this email.");
                        break;
                    case "WPE":
                        passwordError.text("Your password is incorrect.");
                        break;
                    case "OK":
                        document.location.href = "index.php";
                        break;
                }
            }
        );

        event.preventDefault();
    });
});

function matchPasswords() {
    return $("#password").val() === $("#password_confirmation").val();
}
