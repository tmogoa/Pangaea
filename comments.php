<?php
    session_start();

    if(!isset($_SESSION['userId'])){
        header("Location: login.php");
    }
    
    if(!isset($_GET['id'])){
        header("Location: index.php");
    }

    
    $articleId = (int)$_GET['id'];

    if($articleId == 0)//this is not to be used
    {
        header("Location: index.php");
    }

    spl_autoload_register(function($name){
        $name = strtolower($name);
        require_once(getcwd()."/logic/classes/$name.class.php");
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
        <!--Article Title-->
        <p class="text-2xl font-bold text-center mt-8">How I grew my youtube channel</p>
        
        <div class="flex flex row items-center p-12 ">
            <!--Left-->
            <div>
                <div>
                    <i class="far fa-thumbs-up text-gray-400"></i>
                    <p class="text-gray-500">4.4K</p>
                </div>
    
                <div>
                    <i class="fas fa-share text-gray-400"></i>
                    <p class="text-gray-500">18</p>
                </div>
            </div>
    
            <!--Right-->
            <div class="p-10 m-2 border rounded-md shadow-lg">
                
                <span style='font-size:25px; color: #000000;'>&#10006;</span>
                
                <!--Comment 1-->
                <div class="flex "> 
                    <img src="\App\assets\img\profile.jpeg" alt="" class="rounded-full h-20 w-20">
                    <p class="p-8">Levi Zwannah</p>
                    <p class="p-8">9 April, 2019 3:53 pm</p>
                </div>

              
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
                    <div class="flex space-x-2">
                        <div>
                            <i class="far fa-thumbs-up text-gray-400"></i>
                            <p class="text-gray-500 text-xs">4.4K</p>
                        </div>
                        <div>
                            <i class="fas fa-comments text-gray-400"></i>
                        </div>
                    </div>
                    <!--Comments to comment-->
                    <div class="p-12 mr-8">
                        <div class="flex "> 
                            <img src="\App\assets\img\profile.jpeg" alt="" class="rounded-full h-20 w-20">
                            <p class="p-8">Levi Zwannah</p>
                            <p class="p-8">9 April, 2019 3:53 pm</p>
                        </div>
                        <div>
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
                            <div class="flex justify-between">
                                <div>
                                    <i class="far fa-thumbs-up text-gray-400"></i>
                                    <p class="text-gray-500">4.4K</p>
                                </div>
                                <div>
                                    <i class="fas fa-comments text-gray-400"></i>
                                </div>
                            </div>
                       </div>
                    </div>
            </div>

            <!--Comment 2-->
            <div class="flex "> 
                <img src="\App\assets\img\profile.jpeg" alt="" class="rounded-full h-20 w-20">
                <p class="p-8">Levi Zwannah</p>
                <p class="p-8">9 April, 2019 3:53 pm</p>
            </div>

          
            <div>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
                <div class="flex space-x-2">
                    <div>
                        <i class="far fa-thumbs-up text-gray-400"></i>
                        <p class="text-gray-500 text-xs">4.4K</p>
                    </div>
                    <div>
                        <i class="fas fa-comments text-gray-400"></i>
                    </div>
                </div>
        </div>
    
    </body>
</html>