<?php
    session_start();
    if(!isset($_SESSION['userId'])){
        header("Location: login.php");
    }

    spl_autoload_register(function($name){
        $name = strtolower($name);
        require_once("logic/classes/$name.class.php");
    });
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

         <!--Title-->
         <div>
            <p class="p-2 text-center text-gray-500 text-lg mb-6 mt-6">Subscriptions</p>
        </div>
        
        <div class="flex justify-center md:fixed md:left-64 md:top-1/3 mb-6">
            <button id="pay-btn" class="trigger rounded text-white bg-blue-500 py-2 px-4 text-xs font-bold">Pay subscription</button>
        </div>

        <div class="flex flex-col w-4/12 mx-auto text-gray-500">
            
            <?php
                $sql = "SELECT subPaymentId from subscriptionPayment where readerId = ? and `month` = ? and `year` = ? and resultCode = ?";
                $conn = Utility::makeConnection();
                $stmt = $conn->prepare($sql);

                for ($i=1; $i <= 12; $i++) { 
                    $month = date("F", mktime(0,0,0, $i));
                    $year = date("Y");
                    $values = [$_SESSION['userId'], $month, $year, 1];

                    $paid = false;
                    $current = "";
                    
                    if(date("F") == $month){
                        $current = "CURRENT";
                    }
                    $stmt->execute($values);
                    $result = $stmt->fetchAll();
                    if($result){
                        $paid = true;

                    }
            ?>
                    <div class="flex flex-row border p-4 rounded shadow items-center mb-4">
                        <span class="mr-1"><?php echo $year ?></span>
                        <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full"></span>
                        <span class="mr-3"><?php echo $month ?></span>
                        <span class="rounded text-white bg-green-500 py-1 px-2 text-xs font-bold <?php if($current == ""){ echo "hidden";}?>"><?php echo $current ?></span>
                        <span class="flex-grow flex justify-end items-center">
                           <?php
                            if($paid){
                                ?>
                                <i class="fas fa-check text-green-500"></i>
                                <?php
                            }
                            else{
                                ?>
                                 <i class="fas fa-times text-red-500"></i>
                                <?php
                            }
                            ?>
                        </span>
                    </div>
            <?php
                if($current == "CURRENT"){
                    break;
                }
             }
            ?>
        </div>
        

        <!-- Modal -->


        <div class="modal">
            <div
                class="modal-content flex flex-col sm:flex bg-gray-50 rounded-md w-10/12 sm:w-6/12 sm:mx-auto sm:mt-6 shadow  mb-10"
            >
                 <!--Header-->
            <div class="flex justify-between p-2 items-center border-b">
                <div class="m-2 py-1 px-2 text-gray-500 text-lg">Subscribing</div>
                <div class="flex justify-center items-center rounded-full hover:bg-gray-200 m-2 p-2">
                    <span class="text-gray-500 close-button flex justify-center items-center my-auto">&times;</span>
                </div>
            </div>

                <div class="m-2">
                    <p class="text-gray-500 p-2">Only 200 KES to read unlimited articles this for a month</p>
                    <p class="text-gray-500 p-4 text-sm">We've just sent a prompt to your phone to confirm payment of Ksh. 200 for this month. Once you have paid click `I have paid` below.</p>


                    <!--Button-->
                    <div class="flex justify-end m-2">
                        <button 
                            id="confirm-btn"
                            class="flex rounded text-white bg-blue-500 py-2 px-4 text-xs font-bold"
                        >
                        <img src="assets/img/grid2.svg" id="publish-loader" class="hidden mr-2" width="12" alt="..." class="mr-3">I have paid
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--Box with everything-->
        <!-- <div class="flex flex-col sm:flex bg-gray-50 rounded-md w-full sm:w-8/12 sm:mx-auto sm:mt-6 shadow overflow-hidden mb-10"> -->
           
            <!--Subscriptions list-->
            <!-- <div>
                <div class="flex justify-between p-6 m-2 border rounded-md bg-white ">
                    <p class="mr-8">2020 August</p> -->
                    <!--Current Subscription-->
                    <!-- <span class="border-0 mr-8 ml-8 rounded-md p-1 bg-green-600 text-white text-sm">CURRENT</span> -->
                    <!--State-->
                    <!-- <span style="font-family: wingdings;color: #00A650; font-size: 200%;">&#252;</span>
                </div>

                <div class="flex justify-between p-6 m-2 border rounded-md bg-white ">
                    <p class="mr-8">2020 August</p> -->
                    <!--State-->
                    <!-- <span style="font-family: wingdings;color: #00A650; font-size: 200%;">&#252;</span>
                </div>

                <div class="flex justify-between p-6 m-2 border rounded-md bg-white ">
                    <p class="mr-8">2020 August</p> -->
                    <!--State-->
                    <!-- <span style='font-size:25px; color: #FF3131;'>&#10006;</span>
                </div>

                <div class="flex justify-between p-6 m-2 border rounded-md bg-white ">
                    <p class="mr-8">2020 August</p> -->
                    <!--State-->
                    <!-- <span style="font-family: wingdings;color: #00A650; font-size: 200%;">&#252;</span>
                </div>

            </div>
        </div> -->
    <script src="./assets/js/read.js"></script>

    </body>
        
</html>