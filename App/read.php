<?php
session_start();
/**
 * If sign up is needed to read the article, please indicate it here.
 * @Levi no signup needed
 * We will check if the user has reached his/her number of free reads. If yes, then redirect to
 * payment page. However, this logic is not implemented yet.
 */

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

 $article = new Article($articleId);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@300;400;600;700&display=swap" rel="stylesheet"/>

    <link rel="icon" href="assets/img/logo.svg" type="image/svg" sizes="16x16" />
    <link rel="stylesheet" href="./assets/css/style.css" />

    <script src="https://kit.fontawesome.com/1239ccb1ec.js" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
    <title>Pangaea</title>
</head>

<body class="bg-gray-50 bg-opacity-30">
    <!-- html files will be injected into the divs defined here-->
    <div id="navbar"></div>

    <!-- JS file injections-->
    <script>
        $(function() {
            $("#navbar").load("./components/navbar.php");
        });
    </script>

    <div class="flex flex-row p-12">
        <!--Left-->
        <div class="flex flex-col fixed top-2/4">
            <div class="flex flex-row items-center text-red-500 mb-4">
                <img src="assets/img/clap.svg" class="w-7" alt="clapping">
                <!-- <div>Icons made by <a href="https://www.flaticon.com/authors/darius-dan" title="Darius Dan">Darius Dan</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div> -->
                <span class="text-gray-500"><?php echo Utility::thousandsCurrencyFormat($article->getApplauds())?></span>
            </div>

            <div class="flex flex-row items-center">
                <i class="far fa-comment-dots text-3xl text-gray-300"></i>
                <span class="text-gray-500"><?php echo Utility::thousandsCurrencyFormat($article->getNumberOfComments())?></span>
            </div>
        </div>

        <!--Right-->
        <div class="w-8/12 p-6 text-lg prose lg:prose-2xl font-serif mx-auto m-1">
            <h2><?php echo $article->getTitle() ?></h2>
            <div id="output">
            </div>
        </div>
            
    </div>
</body>
    <script src="./assets/js/parser.js"></script>
    <script>
        console.log(<?php echo htmlspecialchars_decode($article->getBody()) ?>);
        parser = new Parser();
        const renderable = parser.parse(<?php
        echo  htmlspecialchars_decode($article->getBody());
    ?>);
    document.getElementById("output").innerHTML = renderable;
    </script>
</html>