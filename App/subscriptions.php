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
                        <span class="rounded text-white bg-green-500 py-1 px-2 text-xs font-bold">CURRENT<?php echo $current ?></span>
                        <span class="flex-grow flex justify-end items-center">
                           <?php
                            if($paid){
                                ?>
                                <i class="fas fa-check text-green-500"></i>
                                <?php
                            }
                            else{
                                ?>
                                 <i class="fas fa-check text-red-500"></i>
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

    </body>
        
</html>