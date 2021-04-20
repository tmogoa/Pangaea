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
        <script src="assets/js/main.js"></script>
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

        <div class="flex flex-col sm:flex bg-gray-100 rounded-md w-full sm:w-8/12 sm:mx-auto sm:mt-6 shadow overflow-hidden mb-10">
            <!--Profile picture. *REMEMBER TO CENTRE*-->
            <div class="mx-20 m-12">
                <img src="\App\assets\img\profile.jpeg" alt="" srcset="" class="rounded-full h-24 w-24">
            </div>
            <div class="text-right m-2">
                <button
                        class="bg-red-400 hover:bg-green-700 text-white font-bold py-2 px-4 border border-red-400 rounded"
                    >
                        Edit
                </button>
            </div>
            <!--Profile Details-->
            <div class="">
                <div class="rounded-md shadow-lg bg-white m-2">
                    <p class="text-sm text-gray-500 font-bold">NAME</p>
                    <p>Susan Ng'ang'a</p>
                </div>

                <div class="rounded-md shadow-lg bg-white m-2">
                    <p class="text-sm text-gray-500 font-bold">USERNAME</p>
                    <p>snganga@pangaea</p>
                </div>

                <div class="rounded-md shadow-lg bg-white m-2">
                    <p class="text-sm text-gray-500 font-bold">BIO</p>
                    <p>Susan is a young creative lady that believes in the power that a pen and open mind hold.</p>
                </div>

                <div class="rounded-md shadow-lg bg-white m-2">
                    <p class="text-sm text-gray-500 font-bold">MPESA NUMBER</p>
                    <p>+254 708-502-805</p>
                </div>

                <div class="text-right m-2">
                    <button
                        class="bg-green-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-greenma-900 rounded"
                    >
                        Save
                </button>
                    
                </div>


            </div>
        </div>
    </body>
</html>