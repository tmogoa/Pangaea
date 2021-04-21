<?php
    session_start();
    if(!isset($_SESSION['userId'])){
        header("Location: login.php");
    }else{
        //require_once "logic/procedures/addArticle.php";
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
            </div>
        </div>


        <input type="text" name="user-id" id="user-id" value="<?php echo $_SESSION['userId'] ?>" hidden>
        <input type="text" name="article-id" id="article-id" value="<?php echo $articleId ?>" hidden>






        <!-- Modal -->


        <div class="fixed left-0 top-0 w-full h-full bg-opacity-50 bg-black z-50 scale-110">
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 flex flex-col sm:flex bg-gray-50 rounded-md w-full sm:w-8/12 sm:mx-auto sm:mt-6 shadow overflow-hidden mb-10"
            >
                <!--Subtitle-->
                <div class="py-2 px-2">
                    <label for="headertag">Add tags to your article</label>
                </div>

                <div class="m-2">
                    <!--Input-->
                    <div class="text-sm text-gray-500 py-2 px-4 rounded-3xl border">
                        <input
                            type="text"
                            class="block w-full border-0 focus:outline-none bg-gray-50"
                            placeholder="Enter tags here"
                        />
                    </div>

                    <!--Suggestions-->
                    <div class="flex items-center p-6 m-0 sm:w-8/12 w-full space-x-2 ">
                        <div class="border p-4">Software Engineering</div>
                        <div class="border p-4">Best Practices</div>
                        <div class="border p-4">Web Development</div>
                    </div>
                    <!--Checkbox-->
                    <div
                        class="text-xs max-w-none text-gray-500 text-justify border: outline-none"
                    >
                        <input
                            type="checkbox"
                            name="permission"
                            id="permission"
                            value="Granted"
                        />
                        <label for="permission"
                            >Allow Pangaea curators to curate my article to reach a
                            bigger audience. Without checking this your article
                            won’t earn money.</label
                        >
                    </div>

                    <!--Button-->
                    <div class="text-right m-2">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded"
                        >
                            Go Live
                        </button>
                    </div>
                </div>
            </div>
        </div>







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
