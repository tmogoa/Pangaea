<?php
    session_start();
    if(!isset($_SESSION['userId'])){
        header("Location: login.php");
    }else{
        require_once "logic/procedures/addArticle.php";
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap"
            rel="stylesheet"
        />

        <link
            href="https://fonts.googleapis.com/css2?family=Newsreader:wght@300;400;600;700&display=swap"
            rel="stylesheet"
        />

        <link
            rel="icon"
            href="assets/img/logo.svg"
            type="image/svg"
            sizes="16x16"
        />
        <link rel="stylesheet" href="./assets/css/style.css" />

        <script
            src="https://kit.fontawesome.com/1239ccb1ec.js"
            crossorigin="anonymous"
        ></script>
        <title>Pangaea</title>
    </head>
    <body>
        <!-- html files will be injected into the divs defined here-->
        <div id="navbar"></div>
        <div class="flex flex-col">
            <div 
                id = "loaderContainer"
                class="flex sticky top-0 flex-row justify-end w-full mt-3 py-6 px-6 sm:px-36"
            >
                <div class="hidden" id="loader">
                    <div class="flex flex-row items-center">
                        <img src="assets/img/grid.svg" width="16" alt="..." class="mr-3">
                        <p class="text-gray-500">Saving</p>
                    </div>
                </div>
                <div>
                <button
                    class="rounded text-white bg-blue-500 py-2 px-4 text-xs font-bold"
                    onclick="saveArticle()"
                >
                    Publish
                </button>
                </div>
            </div>
            <div class="mt-3 flex flex-col">
                <div
                    class="w-full sm:w-6/12 sm:mx-auto p-6 flex flex-col items-center"
                >
                    <div class="w-full mb-3">
                        <span
                            id="title_label"
                            class="text-xs font-bold text-gray-500 hidden"
                            >Title</span
                        >
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="font-serif text-lg w-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-200 rounded"
                            placeholder="Title"
                        />
                    </div>
                    <div class="w-full">
                        <span
                            id="subtitle_label"
                            class="text-xs font-bold text-gray-500 hidden"
                            >Subtitle</span
                        >
                        <input
                            type="text"
                            name="subtitle"
                            id="subtitle"
                            class="font-serif text-lg w-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-200 rounded"
                            placeholder="Subtitle"
                        />
                    </div>
                </div>
                <div
                    id="editorjs"
                    class="w-full shadow-sm border rounded sm:w-8/12 sm:mx-auto p-6 font-serif text-lg prose lg:prose-xl"
                ></div>

                <div id="parsedText" class="w-full shadow-sm border rounded sm:w-8/12 sm:mx-auto p-6 font-serif text-lg prose lg:prose-xl mt-6">

                </div>
            </div>
        </div>


        <input type="text" name="user-id" id="user-id" value="<?php echo $_SESSION['userId'] ?>" hidden>
        <input type="text" name="article-id" id="article-id" value="<?php echo $articleId ?>" hidden>

        <!-- JS file injections-->
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.3.0"></script>
        <script src="https://cdn.jsdelivr.net/npm/editorjs-html@3.0.3/build/edjsHTML.js"></script>
        <script src="./assets/js/write.js"></script>
    </body>
</html>
