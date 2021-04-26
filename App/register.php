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

        <div
            class="flex flex-col sm:flex sm:flex-row-reverse bg-gray-50 rounded-md w-full sm:w-8/12 sm:mx-auto sm:mt-6 shadow overflow-hidden mb-10"
        >
            <div
                class="flex flex-col justify-center items-center bg-white p-6 m-0 sm:w-6/12 w-full"
            >
                <img
                    src="./assets/img/logo.svg"
                    alt="logo"
                    class="w-32 sm:w-64"
                />
                <span class="text-blue-900 text-3xl">Pangaea</span>
                <p class="mt-4 text-gray-500">Binge-worthy reads.</p>
            </div>

            <div class="flex flex-col flex-grow items-center p-6">
                <div
                    class="w-full p-4 flex flex-row justify-start items-center mb-4"
                >
                    <img src="./assets/img/logo.svg" alt="logo" class="w-12" />
                    <span class="text-xs font-bold text-gray-500"
                        >Registration</span
                    >
                </div>

                <form action="" id="registerForm" method="POST">
                    <!-- <div class="flex flex-col mb-4">
                        <label
                            for="firstname"
                            class="text-gray-500 text-xs font-bold mb-2 ml-2"
                            >Firstname</label
                        >
                        <input
                            type="text"
                            name="firstname"
                            id="firstname"
                            class="text-sm text-gray-500 py-2 px-4 rounded-3xl border focus:outline-none"
                            placeholder="firstname"
                            required
                        />
                    </div>

                    <div class="flex flex-col mb-4">
                        <label
                            for="lastname"
                            class="text-gray-500 text-xs font-bold mb-2 ml-2"
                            >lastname</label
                        >
                        <input
                            type="text"
                            name="lastname"
                            id="lastname"
                            class="text-sm text-gray-500 py-2 px-4 rounded-3xl border focus:outline-none"
                            placeholder="lastname"
                            required
                        />
                    </div> -->

                    <div class="mb-4 text-red-500 text-xs ml-2" id="error">

                    </div>

                    <div class="flex flex-col mb-6">
                        <label
                            for="email"
                            class="text-gray-500 text-xs font-bold mb-2 ml-2"
                            >Email address</label
                        >
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="text-sm text-gray-500 py-2 px-4 rounded-3xl border focus:outline-none"
                            placeholder="Your email address"
                            required 
                            autocomplete="email"
                        />
                        <span
                            id="email_error"
                            class="text-red-500 text-xs ml-2"
                        ></span>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label
                            for="password"
                            class="text-gray-500 text-xs font-bold mb-2 ml-2"
                            >Password</label
                        >
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="text-sm text-gray-500 py-2 px-4 rounded-3xl border focus:outline-none"
                            placeholder="Choose a password"
                            required
                            autocomplete="new-password"
                        />
                        <span
                            id="password_error"
                            class="text-red-500 text-xs ml-2"
                        ></span>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label
                            for="password_confirmation"
                            class="text-gray-500 text-xs font-bold mb-2 ml-2"
                            >Confirm password</label
                        >
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="text-sm text-gray-500 py-2 px-4 rounded-3xl border focus:outline-none"
                            placeholder="Repeat your password"
                            required
                            autocomplete="new-password"
                        />
                        <span
                            id="password_confirmation_error"
                            class="text-red-500 text-xs ml-2"
                        ></span>
                    </div>

                    <!-- <div class="flex flex-col mb-4">
                        <label
                            for="MPesa number"
                            class="text-gray-500 text-xs font-bold mb-2 ml-2"
                            >MPesa number</label
                        >
                        <input
                            type="tel"
                            name="MPesa number"
                            id="MPesa number"
                            class="text-sm text-gray-500 py-2 px-4 rounded-3xl border focus:outline-none"
                            placeholder="MPesa number"
                            required
                        />
                    </div> -->

                    <div class="flex flex-col mb-6">
                        <button
                            class="rounded-md text-white bg-blue-500 w-full py-2 px-4"
                        >
                            Create account
                        </button>
                        <p class="text-xs max-w-xs text-gray-500 text-center">
                            By creating an account you agree to our Terms and
                            Conditions and Privacy Policy.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
