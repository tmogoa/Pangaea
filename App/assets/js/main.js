$(function () {
    $("form#registerForm").submit(function () {
        if (matchPasswords()) {
            $.post(
                "logic/procedures/signup.php",
                $("form#registerForm").serialize(),
                function (data) {
                    console.log(data);
                    switch (data) {
                        case "UEE":
                            break;
                        case "PLSE":
                            break;
                        case "PNE":
                            break;
                        case "PLLE":
                            break;
                        case "PULE":
                            break;
                        case "NEE":
                            break;
                        case "NPE":
                            break;
                        case "EEE":
                            break;
                        case "SQE":
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

    $("form#loginForm").submit(function () {
        $.post(
            "logic/procedures/login.php",
            $("form#loginForm").serialize(),
            function (data) {
                console.log(data);
                switch (data) {
                    case "WEE":
                        break;
                    case "WPE":
                        break;
                    case "OK":
                        document.location.href = "index.php";
                        break;
                }
            }
        );

        return false;
    });
});

function matchPasswords() {
    return $("#password").val() === $("#password_confirmation").val();
}
