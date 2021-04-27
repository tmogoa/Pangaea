$("#clapper").mousedown(function () {
    $(this).addClass("animate-ping");
});

//console.log(dayjs().format("D MMM, YYYY h:mma"));

$("#clapper").mouseup(function () {
    $(this).removeClass("animate-ping");
    sendArticleId();
});

function sendArticleId() {
    const url = "logic/procedures/applaudArticle.php";
    $("#applauds").addClass("animate-ping");
    $.post(url, { id: $("input#article-id").val() }, function (data) {
        const result = JSON.parse(data);
        if (result.status === "OK") {
            $("#applauds").text(result.applauds);
            $("#applauds").removeClass("animate-ping");
        }
    });
}

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

//Posting comment
$("#post-btn").click(function () {
    const url = "logic/procedures/addComment.php";
    $.post(
        url,
        {
            articleId: $("input#article-id").val(),
            comment: $("#comment-input").val(),
        },
        function (data) {
            console.log(data);
        }
    );

    addComment($("#comment-input").val());
});

function addComment(comment) {
    console.log($("#avatar-img").val());
    $("#comments").append(`
        <div class="flex flex-row text-gray-500 p-4 w-full justify-center">
            <div class="mx-2">
                <div class="w-10 h-10 rounded-full overflow-hidden">
                    <img src="${$(
                        "#avatar-img"
                    ).val()}" alt="" class="h-full w-full object-cover">
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex flex-col sm:flex-row sm:items-center text-sm mb-1">
                    <span class="pr-1">${$("#firstname").val()} ${$(
        "#lastname"
    ).val()}</span>
                    <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full hidden sm:inline"></span>
                    <span class="text-xs">${dayjs().format(
                        "D MMM, YYYY h:mma"
                    )}</span>
                </div>
                <div class="text-xs ml-2">${comment}</div>
            </div>
        </div>`);
}
