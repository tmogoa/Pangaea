$("form").submit(function () {
    if (!matchPasswords()) {
        $("#password-error").text("Passwords don't match!");
    } else {
        const url = "logic/procedures/editProfile.php";
        $.post(url, $(this).serialize(), function (data) {
            console.log(data);
        });
    }
    return false;
});

function matchPasswords() {
    return $("#password-confirmation").val() === $("#new-password").val();
}
