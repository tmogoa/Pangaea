let editor;
let timeoutId;
let parser;
$(function () {
    parser = edjsHTML();

    $("#title").change(function () {
        if ($("#title").val() != "") {
            $("#title_label").css("display", "block");
        } else {
            $("#title_label").css("display", "none");
        }
    });
    $("#subtitle").change(function () {
        if ($("#subtitle").val() !== "") {
            $("#subtitle_label").css("display", "block");
        } else {
            $("#subtitle_label").css("display", "none");
        }
    });
    $("#navbar").load("./components/navbar.php");

    editor = new EditorJS({
        /**
         * Id of Element that should contain the Editor
         */
        holder: "editorjs",

        /**
         * Available Tools list.
         * Pass Tool's class or Settings object for each Tool you want to use
         */
        tools: {
            header: Header,
            delimiter: Delimiter,
            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
                config: {
                    placeholder: "OK, write something binge-worthy...",
                },
            },
            embed: {
                class: Embed,
                inlineToolbar: true,
            },
            // image: SimpleImage,
            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: "logic/procedures/uploadImage.php", //Your backend file uploader endpoint
                        byUrl: "http://localhost:8008/fetchUrl", // Your endpoint that provides uploading by Url
                    },
                },
            },
        },
        embed: Embed,
        image: SimpleImage,
        /**
         * Previously saved data that should be rendered
         */
        data: {},
    });
});

function saveArticle(elem) {
    const urlToAutoSaver = "logic/procedures/editArticle.php";
    switch (elem.attr("id")) {
        case "editorjs":
            editor
                .save()
                .then((output) => {
                    //getting json from the editor

                    $.post(
                        "test.php",
                        { articleBody: elem.val() },
                        function (data) {
                            showLoader(false);
                        },
                        "json"
                    );

                    //$("#parsedText").html(parser.parse(output));

                    //console.log("data:" + output);
                })
                .catch((error) => {
                    console.log("error:" + error);
                });
            break;
        case "title":
            showLoader(true);

            $.post(
                "test.php",
                { title: elem.val() },
                function (data) {
                    showLoader(false);
                },
                "json"
            );

            break;
        case "subtitle":
            showLoader(true);

            $.post(
                "test.php",
                { subtitle: elem.val() },
                function (data) {
                    showLoader(false);
                },
                "json"
            );

            break;
    }
}

function showLoader(visible) {
    if (visible) {
        $("#loaderContainer").removeClass("justify-end");
        $("#loaderContainer").addClass("justify-between");
        $("#loader").removeClass("hidden");
    } else {
        $("#loaderContainer").removeClass("justify-between");
        $("#loaderContainer").addClass("justify-end");
        $("#loader").addClass("hidden");
    }
}

function listenForChanges() {
    if (timeoutId) {
        clearTimeout(timeoutId);
    }

    timeoutId = setTimeout(() => {
        //save article to db after 1s inactivity
        saveArticle($(this));
    }, 1000);
}

function autosave() {
    $("#editorjs").keypress(listenForChanges);
    $("#title").keypress(listenForChanges);
    $("#subtitle").keypress(listenForChanges);
}

autosave();

// article id
//console.log($("#user-id").val());
