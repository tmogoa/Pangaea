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

        <div class="w-6/12 mx-auto flex flex-col p-6 border rounded-sm mt-6 shadow text-gray-500 mb-6">

            <div class="flex flex-col justify-center items-center mb-3">
                <div class="w-24 h-24 overflow-hidden rounded-full border shadow-lg mb-2">
                    <img src="assets/img/larry.jpeg" class="w-full h-full object-cover" alt="">
                </div>
                <button 
                    id="edit-btn"
                    class="rounded text-white bg-blue-500 py-2 px-4 text-xs font-bold"
                >
                    Change
                </button>
            </div>


            <div class="flex flex-row p-2 mb-4 rounded-sm justify-between">
                <div class="flex flex-col">
                    <span class="text-xs font-bold mb-1">firstname</span>
                    <input placeholder="Firstname" type="text" class="text-md p-2 border focus:outline-none rounded" value="Larry Page" autofocus>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold mb-1">lastname</span>
                    <input placeholder="Lastname" type="text" class="text-md p-2 border focus:outline-none rounded" value="Larry Page" >
                </div>
            </div>

            <div class="flex flex-col p-2 mb-4 rounded-sm">
                <span class="text-xs font-bold mb-1">email</span>
                <input placeholder="Your email" type="email" class="text-md p-2 border focus:outline-none rounded" value="lpage@google.com" >
            </div>

            <div class="flex flex-row p-2 mb-4 rounded-sm justify-between">
                <div class="flex flex-col">
                    <span class="text-xs font-bold mb-2">old password</span>
                    <input type="password" class="text-md p-2 border focus:outline-none rounded" id="old-password" placeholder="Enter old password">
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold mb-2">new password</span>
                    <input type="password" class="text-md mb-2 p-2 border focus:outline-none rounded" id="new-password" placeholder="Enter a new password">
                    <span class="text-xs font-bold mb-2">confirm password</span>
                    <input type="password" class="text-md p-2 border focus:outline-none rounded" id="password-confirmation" placeholder="Confirm password">
                </div>
            </div>

            <div class="flex flex-col p-2 mb-4 rounded-sm">
                <span class="text-xs font-bold mb-1">mpesa number</span>
                <input placeholder="Your phone number" type="text" class="text-md p-2 border focus:outline-none rounded" value="+254 708-502-805" >
            </div>

            <div class="flex flex-row justify-end mb-3">
                <button 
                    id="edit-btn"
                    class="rounded text-white bg-blue-500 py-2 px-4 text-xs font-bold"
                >
                    Save
                </button>
            </div>
        </div>

        <!-- <div class="flex flex-col sm:flex bg-gray-100 rounded-md w-full sm:w-8/12 sm:mx-auto sm:mt-6 shadow overflow-hidden mb-10">
            
            <div class="mx-20 m-12">
                <img src="assets\img\larry.jpeg" alt="" srcset="" class="rounded-full h-24 w-24">
            </div>
            <div class="text-right m-2">
                <button
                        class="bg-red-400 hover:bg-green-700 text-white font-bold py-2 px-4 border border-red-400 rounded"
                    >
                        Edit
                </button>
            </div>
            
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
        </div> -->
    </body>
</html>