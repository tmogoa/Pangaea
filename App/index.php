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

    
    $sql = "SELECT article.articleId, writerId, title, subtitle, body, featured_image, article.updated_at, topic from article left join (select * from articleTopics inner join articleTags on aTopicId = tagId LIMIT 1) as tagsTable on tagsTable.articleId = article.articleId where publishStatus='published' order by article.updated_at ASC";

    $conn = Utility::makeConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $articles = $stmt->fetchAll();
    //var_dump($articles);
    if($articles){

                $article = $articles[0];
                $writer = new Reader($article['writerId']);
                echo $writer->getFirstName()." ". $writer->getLastName()
                ?>
                    <a href="read.php?id=<?php echo $article['articleId'] ?>">
                    <div class="flex text-gray-500 flex-col md:flex-row bg-white shadow-md rounded justify-between w-10/12 mx-auto md:h-96 mt-6">

                            <div class="w-full h-1/2 mb-2 md:w-6/12 md:h-full overflow-hidden rounded-tl rounded-bl-none rounded-tr md:rounded-tr-none md:rounded-bl flex-shrink-0">
                                <img src="<?php echo $article['featured_image'] ?>" class="h-full w-full object-cover" alt="">
                            </div>

                            <div class="flex flex-col mx-4 p-4 w-full md:w-6/12">
                                <div class="flex flex-row items-center mb-3">
                                    <span class="pr-1 text-xs font-semibold"><?php echo $article['topic'] ?></span>
                                    <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full"></span>
                                    <span class="text-xs"><?php echo date("F d, Y", strtotime($article['updated_at'])) ?></span>
                                </div>
                                <div class="w-full flex flex-col mb-3">
                                    <span class="mb-2 text-lg font-bold md:text-3xl"><?php echo $article['title'] ?></span>
                                    <span class="text-md md:text-lg mb-1"><?php echo $article['subtitle'] ?></span>
                                    <div class="hidden lg:block text-sm whitespace-normal h-24 overflow-hidden overflow-ellipsis flex-grow-0 px-4" id="output"></div>
                                </div>
                                <div class="flex-grow flex flex-col justify-end">
                                    <div class="flex flex-row items-center">
                                        <div class="w-8 h-8 rounded-full overflow-hidden mr-2">
                                            <img src="storage/images/larry.jpeg" alt="" class="h-full w-full object-cover">
                                        </div>
                                        <div class="text-xs bg-red-400"><?php echo $writer->getFirstName()." ". $writer->getLastName() ?></div>
                                    </div>
                                </div>
                            </div>

                    </div>
                    </a>

                    
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-gray-500 w-10/12 mx-auto mt-6 mb-4">
                                <?php 
                                    for($i = 1; $i < count($articles); $i++) { 
                                        $article = $articles[$i];
                                ?>
                                        <a href="read.php?id=<?php echo $article['articleId'] ?>"><div class="flex justify-center items-center">
                                            <div class="flex flex-col p-4 sm:w-96 m-1 rounded justify-center bg-white shadow-md">
                                                <div class=" mb-2 w-11/12 sm:w-80 sm:h-48 overflow-hidden rounded">
                                                    <img src="<?php echo $article['featured_image'] ?>" class="h-full w-full object-cover" alt="<?php 
                                                    echo $article['title'];
                                                    ?>">
                                                </div>
                                                <div class="flex flex-row items-center mb-1">
                                                    <span class="pr-1 text-xs font-semibold"><?php echo $article['topic'] ?></span>
                                                    <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full hidden sm:inline"></span>
                                                    <span class="text-xs"><?php echo date("F d, Y", strtotime($article['updated_at'])) ?></span>
                                                </div>
                                                <div class="w-full text-sm flex flex-col">
                                                    <span class="font-bold mb-1"><?php echo $article['title'] ?></span>
                                                    <span class="text-xs"><?php echo $article['subtitle'] ?></span>
                                                </div>
                                            </div>
                                        </div></a>
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
    }


 ?>
        <script src="./assets/js/parser.js"></script>
        <script>
            parser = new Parser();
            const renderable = parser.parse(<?php echo $article['body']; ?>);
            document.getElementById("output").innerHTML = renderable;
        </script>
    </body>
</html>
