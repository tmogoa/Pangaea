<?php
    session_start();
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

        <!-- JS file injections-->
        <script>
            $(function () {
                $("#navbar").load("./components/navbar.php");
            });
        </script>

        <!--Box with everything-->
        <div
            class="flex flex-col sm:flex bg-gray-50 rounded-md w-full sm:w-8/12 sm:mx-auto sm:mt-6 shadow overflow-hidden mb-10"
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
                <div
                    class="flex items-center p-6 m-0 sm:w-8/12 w-full space-x-2 "
                >
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
    </body>
</html>
