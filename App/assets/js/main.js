$(function () {
    $("form#registerForm").submit(function () {
        if (matchPasswords()) {
            $.post(
                "logic/procedures/signup.php",
                $("form#registerForm").serialize(),
                function (data) {
                    console.log(data);
                }
            );
        } else {
            $("#password_error").text("Passwords do not match.");
            $("#password_confirmation_error").text("Passwords do not match.");
        }
        return false;
    });
});

function matchPasswords() {
    return $("#password").val() === $("#password_confirmation").val();
}
