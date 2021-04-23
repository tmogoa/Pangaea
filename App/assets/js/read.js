$("#clapper").mousedown(function () {
    $(this).addClass("animate-ping");
});

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
