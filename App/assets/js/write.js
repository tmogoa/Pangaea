let editor;
let timeoutId;
let parser;
const ImageTool = window.ImageTool;
$(function () {
    parser = new Parser();

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
    });
});

function saveArticle(elem) {
    const urlToAutoSaver = "logic/procedures/editArticle.php";
    const articleId = $("#article-id").val();

    switch (elem.attr("id")) {
        case "editorjs":
            editor
                .save() //getting json from the editor
                .then((output) => {
                    parser.renderable = "";
                    parser.parse(output);
                    $("#output").html(parser.renderable);
                    showLoader(true);
                    $.post(
                        urlToAutoSaver,
                        { id: articleId, body: output },
                        function (data) {
                            showLoader(false);
                        }
                    );
                })
                .catch((error) => {
                    console.log("error:" + error);
                });
            break;
        case "title":
            showLoader(true);

            $.post(
                urlToAutoSaver,
                { id: articleId, title: elem.val() },
                function (data) {
                    showLoader(false);
                }
            );

            break;
        case "subtitle":
            showLoader(true);

            $.post(
                urlToAutoSaver,
                { id: articleId, subtitle: elem.val() },
                function (data) {
                    showLoader(false);
                }
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

//Handle adding tags
const tagInput = $("#tag");
const tags = [];
tagInput.keydown(function (event) {
    if (event.keyCode == 13 && tagInput.val() != "") {
        tags.push(tagInput.val());
        const tagIndex = tags.length - 1;
        $("#tags").append(
            ` <span id="${tagIndex}" class="rounded m-2 border p-2 text-gray-500 inline-flex items-center justify-between">
            <span class="text-xs mr-2">
            ${tagInput.val()}
            </span>
            <span class="inline-flex justify-center items-center rounded-full hover:bg-gray-200">
                <button class="x-button inline-flex justify-center items-center focus:outline-none" onclick="removeTag(this)">&times;</button>
            </span>
        </span>`
        );
        tagInput.val("");
        return false;
    }
});

function removeTag(elem) {
    //get parent node
    const tagElem = elem.parentNode.parentNode;
    //hide the tag
    tagElem.classList.add("hidden");
    //get array index of tag
    const index = tagElem.getAttribute("id");
    //remove tag from array by assigning null
    tags[index] = null;
}

$("#go-live").click(function () {
    //Removing null elems from the array
    const finalTags = [];
    tags.forEach((element) => {
        if (element !== null) {
            finalTags.push(element);
        }
    });
    sendTags(finalTags);
});

function sendTags(finalTags) {
    const articleId = $("#article-id").val();
    const url = "logic/procedures/publishArticle.php";
    $.post(
        url,
        { id: articleId, tags: JSON.stringify(finalTags) },
        function (data) {
            //check if publish was okay
            console.log(data);
        }
    );
}
