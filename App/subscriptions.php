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

         <!--Title-->
         <div>
            <p class="p-2 text-center text-gray-400">Subscriptions</p>
        </div>

        <!--Box with everything-->
        <div class="flex flex-col sm:flex bg-gray-50 rounded-md w-full sm:w-8/12 sm:mx-auto sm:mt-6 shadow overflow-hidden mb-10">
           
            <!--Subscriptions list-->
            <div>
                <div class="flex justify-between p-6 m-2 border rounded-md bg-white ">
                    <p class="mr-8">2020 August</p>
                    <!--Current Subscription-->
                    <span class="border-0 mr-8 ml-8 rounded-md p-1 bg-green-600 text-white text-sm">CURRENT</span>
                    <!--State-->
                    <span style="font-family: wingdings;color: #00A650; font-size: 200%;">&#252;</span>
                </div>

                <div class="flex justify-between p-6 m-2 border rounded-md bg-white ">
                    <p class="mr-8">2020 August</p>
                    <!--State-->
                    <span style="font-family: wingdings;color: #00A650; font-size: 200%;">&#252;</span>
                </div>

                <div class="flex justify-between p-6 m-2 border rounded-md bg-white ">
                    <p class="mr-8">2020 August</p>
                    <!--State-->
                    <span style='font-size:25px; color: #FF3131;'>&#10006;</span>
                </div>

                <div class="flex justify-between p-6 m-2 border rounded-md bg-white ">
                    <p class="mr-8">2020 August</p>
                    <!--State-->
                    <span style="font-family: wingdings;color: #00A650; font-size: 200%;">&#252;</span>
                </div>

            </div>
        </div>

    </body>
        
</html>