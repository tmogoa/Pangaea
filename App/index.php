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
    <body class="bg-gray-50">
        <!-- html files will be injected into the divs defined here-->
        <div id="navbar"></div>

        <!-- JS file injections-->
        <script>
            $(function () {
                $("#navbar").load("./components/navbar.php");
            });
        </script>

<?php
    spl_autoload_register(function($name){
        $name = strtolower($name);
        require_once("logic/classes/$name.class.php");
    });

    //listing articles by tags

    
    // $sql = "SELECT articleId, title, subtitle, featured_image, updated_at, tagsTable.* from article left join (select * from articleTopics inner join articleTags on aTopicId = tagId) as tagsTable on tagsTable.articleId = article.articleId Group by tagsTable.tagId";

    //$articles = Utility::queryTable("article", "articleId, title, subtitle, featured_image, updated_at, tagsTable.* from article left join (select * from articleTopics inner join articleTags on aTopicId = tagId LIMIT 1) as tagsTable on tagsTable.articleId = article.articleId Group by tagsTable.tagId", "1 = ?", [1]);

    // if($articles){
    //     foreach($articles as $article){
            
    //     }
    // }


 ?>

        <div class="flex text-gray-500 flex-col md:flex-row bg-white shadow-md rounded justify-between w-10/12 mx-auto md:h-96 mt-6">

            <div class="w-full h-1/2 mb-2 md:w-6/12 md:h-full overflow-hidden rounded-tl rounded-bl-none rounded-tr md:rounded-tr-none md:rounded-bl flex-shrink-0">
                <img src="assets/img/programmer.jpeg" class="h-full w-full object-cover" alt="">
            </div>

            <div class="flex flex-col mx-4 p-4">
                <div class="flex flex-row items-center mb-3">
                    <span class="pr-1 text-xs font-semibold">DEVELOPMENT</span>
                    <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full"></span>
                    <span class="text-xs">July 2, 2020</span>
                </div>
                <div class="w-full flex flex-col mb-3">
                    <span class="mb-2 text-lg font-bold md:text-3xl">Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
                    <span class="text-md md:text-lg mb-1">Maecenas volutpat blandit aliquam etiam erat velit scelerisque in dictum.</span>
                    <div class="hidden lg:block text-sm whitespace-normal overflow-ellipsis flex-grow-0 px-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                </div>
                <div class="flex-grow flex flex-col justify-end">
                    <div class="flex flex-row items-center">
                        <div class="w-8 h-8 rounded-full overflow-hidden mr-2">
                            <img src="storage/images/larry.jpeg" alt="" class="h-full w-full object-cover">
                        </div>
                        <div class="text-xs">Tony Mogoa</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-gray-500 w-10/12 mx-auto mt-6 mb-4">
            <?php 
                for ($i=0; $i < 10; $i++) { 
            ?>
                    <!-- <div class="bg-white shadow-md p-2 rounded">grid grid-cols-3 gap-4</div>
                    <div class="bg-white shadow-md p-2 rounded">grid grid-cols-3 gap-4</div>
                    <div class="bg-white shadow-md p-2 rounded">grid grid-cols-3 gap-4</div> -->
                    <div class="flex justify-center items-center">
                        <div class="flex flex-col p-4 sm:w-96 m-1 rounded justify-center bg-white shadow-md">
                            <div class=" mb-2 w-11/12 sm:w-80 sm:h-48 overflow-hidden rounded">
                                <img src="assets/img/programmer.jpeg" class="h-full w-full object-cover" alt="">
                            </div>
                            <div class="flex flex-row items-center mb-1">
                                <span class="pr-1 text-xs font-semibold">DEVELOPMENT</span>
                                <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full hidden sm:inline"></span>
                                <span class="text-xs">July 2, 2020</span>
                            </div>
                            <div class="w-full text-sm flex flex-col">
                                <span class="font-bold mb-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
                                <span class="text-xs">Maecenas volutpat blandit aliquam etiam erat velit scelerisque in dictum.</span>
                            </div>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
        <?php 
            for ($i=0; $i < 0; $i++) { 
                ?>
            <div class="flex flex-col p-4 sm:w-72 m-1 border rounded justify-center">
                <div class=" mb-2 w-11/12 sm:w-64 sm:h-32 overflow-hidden rounded">
                    <img src="assets/img/programmer.jpeg" class="h-full w-full object-cover" alt="">
                </div>
                <div class="flex flex-row items-center mb-1">
                    <span class="pr-1 text-xs font-semibold">DEVELOPMENT</span>
                    <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full hidden sm:inline"></span>
                    <span class="text-xs">July 2, 2020</span>
                </div>
                <div class="w-full text-sm flex flex-col">
                    <span class="font-bold mb-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
                    <span class="text-xs">Maecenas volutpat blandit aliquam etiam erat velit scelerisque in dictum.</span>
                </div>
            </div>
        <?php
            }
        ?>

    </body>
</html>
