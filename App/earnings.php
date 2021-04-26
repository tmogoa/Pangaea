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

        <div class="text-gray-500 flex flex-col md:flex-row sm:w-8/12 w-full mt-10 mx-auto p-4">
            
            <!--Left --> 
            <div>

                <!-- Lifetime earning-->
                <div class="flex flex-col p-4 w-full sm:w-64 border shadow rounded mb-4">
                    <div>
                        <div class="text-xs font-bold mb-5">LIFETIME EARNING</div>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-green-500 text-xs font-bold">KES</div>
                        <div class="ml-4 text-5xl">3540</div>
                    </div>
                </div>
    
                <!-- paid to -->
                <div class="flex flex-col p-4 w-full sm:w-64 border shadow rounded">
                    <div>
                        <div class="text-xs font-bold mb-5">PAID TO</div>
                    </div>
                    <div class="flex row">
                        <div class="text-green-500"><i class="fas fa-phone-alt"></i></div>
                        <div class="ml-2 text-xl">+254 708-502-805</div>
                    </div>
                </div>
            </div>

            <!-- Right -->
            <div class="flex flex-col flex-grow md:ml-10">
                <div class="text-xs font-bold mb-5">MONTHLY TOTALS</div>
                <?php
                    for ($i=0; $i < 12; $i++) { 
                        
                ?>
                        <div class="flex flex-row border p-4 rounded shadow items-center mb-4">
                            <span class="mr-1">2020</span>
                            <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full"></span>
                            <span class="mr-3">July</span>
                            <span class="rounded text-white bg-green-500 py-1 px-2 text-xs font-bold">CURRENT</span>
                            <div class="flex flex-col flex-grow items-end justify-between h-full">

                                <!--amount -->
                                <div class="flex flex-row">
                                    <span class="text-green-500 text-xs">KES</span>
                                    <span class="ml-1 text-2xl">3540</span>
                                </div>

                                <!-- status: disbursed or pending-->
                                <div class="flex flex-row items-center">
                                    <span class="mr-1 w-2 h-2 bg-yellow-500 rounded-full"></span>
                                    <span class="text-xs">Pending</span>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                ?>
            </div>

        </div>
    </body>
</html>