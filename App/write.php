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
                    class="trigger rounded text-white bg-blue-500 py-2 px-4 text-xs font-bold"
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


        <div class="modal">
            <div
                class="modal-content flex flex-col sm:flex bg-gray-50 rounded-md w-10/12 sm:w-6/12 sm:mx-auto sm:mt-6 shadow overflow-hidden mb-10"
            >
            <div class="flex justify-between p-2">
                 <!--Subtitle-->
                <div class="m-2 py-1 px-2 text-gray-500 text-lg">Add tags to your article</div>
                <div class="flex justify-center items-center rounded-full hover:bg-gray-200 m-2 p-2">
                    <span class="text-gray-500 close-button flex justify-center items-center my-auto">&times;</span>
                </div>
            </div>

                <div class="m-2">
                    <!--Input-->
                    <div class="text-sm text-gray-500 py-2 px-4  flex justify-start">
                        <input
                            type="text"
                            name="tag" 
                            id="tag"
                            class="text-gray-500 py-2 px-4 rounded-3xl border focus:outline-none w-2/3"
                            placeholder="Enter tags here"
                        />
                    </div>

                    <!--Suggestions-->
                    <div class="flex flex-col md:flex-wrap md:flex-row md:items-center p-6 w-full" id="tags">
                        
                    </div>
                    <!--Checkbox-->
                    <div
                        class="text-xs max-w-none text-gray-500 text-justify flex items-start p-6"
                    >
                        <input
                            type="checkbox"
                            name="permission"
                            id="permission"
                            value="Granted"
                        />
                        <label for="permission" class="ml-2"
                            >Allow Pangaea curators to curate my article to reach a
                            bigger audience. Without checking this your article
                            won’t earn money.</label
                        >
                    </div>

                    <!--Button-->
                    <div class="text-right m-2">
                        <button 
                            id="go-live"
                            class="rounded text-white bg-blue-500 py-2 px-4 text-xs font-bold"
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
