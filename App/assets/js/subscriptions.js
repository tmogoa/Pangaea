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
    });
}

$("#pay-btn").click(sendSTKPush);

function confirmPayment() {
    $.post(url, { action: "pay" }, function (data) {
        console.log(data);
    });
}

$("#confirm-btn").click(confirmPayment);
