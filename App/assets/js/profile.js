$("form#profile-details").submit(function () {
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

$("#change-btn").click(function () {
    const fileElem = document.getElementById("file-add");
    fileElem.click();
});

$("#file-add").change(function () {
    const url = "logic/procedures/uploadProfileImage.php";

    var fd = new FormData();
    console.log($(this));
    if ($(this)[0].files.length > 0) {
        fd.append("profileImage", $(this)[0].files[0]);

        $.ajax({
            url,
            type: "post",
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
            },
        });
    } else {
        alert("Please select a file.");
    }
});
