<?php
session_start();

if(!isset($_SESSION['userId'])){
    header("Location: login.php");
}

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

 $user = new Reader($_SESSION['userId']);

 $firstname = empty($user->getFirstName())?"":$user->getFirstName();
 $lastname = empty($user->getLastName())?$user->getEmail():$user->getLastName();
 $profile_img = empty($user->getProfileImage()) ? "assets/img/logo.svg" : $user->getProfileImage();

 $article = new Article($articleId);
 if(!$article->isPublished()){
     header("Location: index.php");
 }

 $writer = new Writer($article->getWriterId());
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

    
    <div class="flex flex-row items-center font-sans w-full sm:w-8/12 px-12 py-4 mx-auto m-1 justify-end">
        <div class="w-8 h-8 rounded-full overflow-hidden mr-2">
            <img src="<?php echo $writer->getProfileImage();?>" alt="" class="h-full w-full object-cover">
        </div>
        <div class="flex flex-col text-xs text-gray-500">
            <span class="font-semibold"><?php echo $writer->getFirstName() . " " . $writer->getLastName();?></span>
            <span class=""><?php echo date("F d, Y", strtotime($article->getDateUpdated())); ?></span>
        </div>
    </div>
    <div class="w-full sm:w-7/12 p-6 text-lg prose lg:prose-2xl font-serif mx-auto m-1">
        <h2><?php echo $article->getTitle() ?></h2>
        <div id="output">
        </div>
    </div>
    
    <div class="flex flex-row items-center justify-evenly sm:flex-col sm:fixed sm:top-2/4 sm:left-10">
        <div class="flex flex-row items-center text-red-500 mb-2 sm:mb-4">
            <img src="assets/img/clap.svg" class="w-12 mr-2 border rounded-full p-1" alt="clapping" id="clapper">
            <!-- <div>Icons made by <a href="https://www.flaticon.com/authors/darius-dan" title="Darius Dan">Darius Dan</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div> -->
            <span class="text-gray-500 text-sm" id="applauds"><?php echo Utility::thousandsCurrencyFormat($article->getApplauds())?></span>
        </div>

        <div class="flex flex-row items-center mb-2">
            <span class="trigger w-12 border rounded-full p-2 inline-flex items-center justify-center mr-2">
                <img src="assets/img/conversation.svg" class="w-10" alt="clapping">
            </span>
            <!-- <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div> -->
            <span class="text-gray-500 text-sm" id="num-comments"><?php echo Utility::thousandsCurrencyFormat($article->getNumberOfComments())?></span>
        </div>
    </div>

    <input type="text" name="article-id" id="article-id" value="<?php echo $articleId?>" hidden/>
    <input type="text" name="firstname" id="firstname" value="<?php echo $firstname?>" hidden/>
    <input type="text" name="lastname" id="lastname" value="<?php echo $lastname?>" hidden/>
    <input type="text" name="avatar" id="avatar-img" value="<?php echo $profile_img?>" hidden/>


    <!-- Modal -->
    <div class="modal overflow-hidden">
            <div
                class="modal-content flex flex-col sm:flex bg-gray-50 rounded-md w-10/12 sm:w-6/12 sm:mx-auto sm:mt-6 shadow mb-10 p-1 overflow-hidden"
            >
                 <!--Header-->
                 <div class="flex flex-col">
                    <div class="flex justify-between p-2 items-center border-b">
                        <div class="mx-2 my-1 py-1 px-2 text-gray-500 text-lg">Comments</div>
                        <div class="flex justify-center items-center rounded-full hover:bg-gray-200 mx-2 my-1 px-2 py-2">
                            <span class="text-gray-500 close-button flex justify-center items-center my-auto">&times;</span>
                        </div>
                    </div>
                    <div class="p-4 flex flex-col">
                        <textarea name="comment-input" id="comment-input" class="border focus:outline-none resize-none rounded p-4 h-15 w-full text-gray-500 text-sm mb-1" placeholder="Write comment here.."></textarea>
                        <div class="flex flex-row justify-end">
                            <button class="rounded text-white bg-blue-500 py-2 px-4 text-xs font-bold" id="post-btn">Post</button>
                        </div>
                    </div>
                 </div>

                <!--Body-->
                <div class="overflow-y-scroll w-full flex flex-col h-80" id="comments">
                <?php
                    $comments = Utility::queryTable("comment", "*", "articleId = ? order by created_at ASC LIMIT 10", [$articleId] );

                    if($comments){
                        foreach($comments as $comment){
                            $commentText = $comment['comment'];
                            $writer = new Reader($comment['readerId']);
                            $commentDate = date("F d, Y h:i:s a", strtotime($comment['created_at']));

                            ?>
                                <div class="flex flex-row text-gray-500 p-4 w-full justify-center">
                                    <div class="mx-2">
                                        <div class="w-10 h-10 rounded-full overflow-hidden">
                                            <img src="<?php echo $writer->getProfileImage() ?>" alt="" class="h-full w-full object-cover">
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex flex-col sm:flex-row sm:items-center text-sm mb-1">
                                            <span class="pr-1"><?php echo $writer->getFirstName()." ". $writer->getLastName() ?></span>
                                            <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full hidden sm:inline"></span>
                                            <span class="text-xs"><?php echo $commentDate ?></span>
                                        </div>
                                        <div class="text-xs ml-2"><?php $commentText ?></div>
                                    </div>
                                </div>
                            <?php
                        }
                    }else{
                        ?>
                        <!--Be the first to comment. Tony write something here-->
                        <?php
                    }


                ?>

                

                    <!-- <div class="flex flex-row text-gray-500 p-4 w-full justify-center">
                        <div class="mx-2">
                            <div class="w-10 h-10 rounded-full overflow-hidden">
                                <img src="storage/images/larry.jpeg" alt="" class="h-full w-full object-cover">
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex flex-col sm:flex-row sm:items-center text-sm mb-1">
                                <span class="pr-1">Larry Page</span>
                                <span class="mr-1 w-1 h-1 bg-gray-500 rounded-full hidden sm:inline"></span>
                                <span class="text-xs">9 April, 2021 3:53pm</span>
                            </div>
                            <div class="text-xs ml-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Risus ultricies tristique nulla aliquet. Maecenas volutpat blandit aliquam etiam erat velit scelerisque in dictum.</div>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>


</body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.10.4/dayjs.min.js" integrity="sha512-0fcCRl828lBlrSCa8QJY51mtNqTcHxabaXVLPgw/jPA5Nutujh6CbTdDgRzl9aSPYW/uuE7c4SffFUQFBAy6lg==" crossorigin="anonymous"></script>
    <script src="./assets/js/parser.js"></script>
    <script src="./assets/js/read.js"></script>
    <script>
        parser = new Parser();
        const renderable = parser.parse(<?php
        echo  htmlspecialchars_decode($article->getBody());
    ?>);
        document.getElementById("output").innerHTML = renderable;
    </script>
</html>