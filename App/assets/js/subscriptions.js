const url = "logic/procedures/makePayment.php";

//modal
const modal = document.querySelector(".modal");
const trigger = document.querySelector(".trigger");
const closeButton = document.querySelector(".close-button");

function toggleModal() {
    modal.classList.toggle("show-modal");
}

function windowOnClick(event) {
    if (event.target === modal) {
        toggleModal();
    }
}

trigger.addEventListener("click", toggleModal);
closeButton.addEventListener("click", toggleModal);
window.addEventListener("click", windowOnClick);

function sendSTKPush() {
    $.post(url, { action: "pay" }, function (data) {
        console.log(data);
        switch (data) {
            case "NPNE":
                break;

            default:
                break;
        }
    });
}

$("#pay-btn").click(function () {
    sendSTKPush();
});

function confirmPayment() {
    $.post(url, { action: "pay" }, function (data) {
        console.log(data);
    });
}

$("#confirm-btn").click(function () {
    confirmPayment();
});

/**
 *
 * @param {sectionName} dest e.g write, read
 * @param {array} params array of objects with query string params as key-value pairs
 */
function routeTo(dest, params) {
    let url = "read.php?";
    params.forEach((param, index) => {
        for (const [key, value] of Object.entries(param)) {
            url += `${key}=${value}`;
            //${index + 1 == params.length ? "" : "&"}`;
        }
        url += `${index + 1 == params.length ? "" : "&"}`;
    });
    document.location.href = url;
}
