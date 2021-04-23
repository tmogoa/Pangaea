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

 <?php
    spl_autoload_register(function($name){
        $name = strtolower($name);
        require_once("logic/classes/$name.class.php");
    });

    //listing articles by tags
    $tags = Utility::queryTable("articleTopics", "*", "1 = ?", [1]);
    $sql = "SELECT articleId, title, subtitle from article inner join (select * from articleTopics inner join articleTags on aTopicId = tagId) as tagsTable on tagsTable.articleId = article.articleId Group by tagsTable.tagId";
    if($tags){
        $conn = Utility::makeConnection();
        $sql = "SELECT articleId from articleTags where tagId = ?";
        $stmt = $conn->prepare($sql);

        foreach($tags as $tag){
            $tagName = $tag['topic'];
            $articles = $stmt->execute([$tags['aTopicId']]);
            if($articles){

            }    
        }
    }
 ?>

        <!--Articles Display 1-->
        <div class="p-12 ">
            <!--Category 1: Editor's picks-->
            <p class="text-gray-400">Editor's Picks</p>
            <div class="grid grid-flow-col grid-cols-3 grid-rows-1 gap-4">
                
                <!--Article 1-->
                <div class="border border-gray-100 rounded-md">
                    <img src="\App\assets\img\person working.jpg" alt="" srcset="">
                    
                    <div class="flex text-gray-500 p-4 m-0 sm:w-8/12 w-full font-bold ">
                        <!--Tag-->
                        <p>Development</p>
                        <!--Date-->
                        <p class="text-right">July 2, 2020</p>
                    </div>
                    <!--Title-->
                   <p class="font-bold">Hybrid vs Nature - Here is how you can chose one over the other</p>
                   
                   <!--Statistics-->
                   <div class="border border-gray-100 rounded-md m-2">
                       <div class="flex space-x-10 ">
                            <p class="text-gray-500">THIS MONTH</p>
                            <a href="http://" class="text-blue-500" >MORE</a>
                       </div>
                       <div class="grid grid-cols-2 divide-x divide-gray-100">
                           <div>
                               <p class="text-green-500 font-bold">KES</p>
                               <p class="text-gray-500 font-bold text-4xl">3489</p>
                           </div>
                           <div>
                               <p class="text-green-500 font-bold">VIEWS</p>
                               <p class="text-gray-500 font-bold text-4xl">2.4K</p>
                           </div>
                       </div>
                   </div>
                </div>

                <!--Article 2-->
                <div class="border border-gray-100 rounded-md">
                    <img src="\App\assets\img\person working.jpg" alt="" srcset="">
                    
                    <div class="flex text-gray-500 p-4 m-0 sm:w-8/12 w-full space-x-2 font-bold ">
                        <!--Tag-->
                        <p>Development</p>
                        <!--Date-->
                        <p class="text-right">July 2, 2020</p>
                    </div>
                    <!--Title-->
                   <p class="font-bold">Hybrid vs Nature - Here is how you can chose one over the other</p>
                   
                   <!--Statistics-->
                   <div class="border border-gray-100 rounded-md m-2">
                       <div class="flex space-x-10 ">
                            <p class="text-gray-500">THIS MONTH</p>
                            <a href="http://" class="text-blue-500" >MORE</a>
                       </div>
                       <div class="grid grid-cols-2 divide-x divide-gray-100">
                           <div>
                               <p class="text-green-500 font-bold">KES</p>
                               <p class="text-gray-500 font-bold text-4xl">3489</p>
                           </div>
                           <div>
                               <p class="text-green-500 font-bold">VIEWS</p>
                               <p class="text-gray-500 font-bold text-4xl">2.4K</p>
                           </div>
                       </div>
                   </div>
                </div>

                <!--Article 3-->
                <div class="border border-gray-100 rounded-md">
                    <img src="\App\assets\img\person working.jpg" alt="" srcset="">
                    
                    <div class="flex text-gray-500 p-4 m-0 sm:w-8/12 w-full space-x-2 font-bold ">
                        <!--Tag-->
                        <p>Development</p>
                        <!--Date-->
                        <p class="text-right">July 2, 2020</p>
                    </div>
                    <!--Title-->
                   <p class="font-bold">Hybrid vs Nature - Here is how you can chose one over the other</p>
                   
                   <!--Statistics-->
                   <div class="border border-gray-100 rounded-md m-2">
                       <div class="flex space-x-10 ">
                            <p class="text-gray-500">THIS MONTH</p>
                            <a href="http://" class="text-blue-500" >MORE</a>
                       </div>
                       <div class="grid grid-cols-2 divide-x divide-gray-100">
                           <div>
                               <p class="text-green-500 font-bold">KES</p>
                               <p class="text-gray-500 font-bold text-4xl">3489</p>
                           </div>
                           <div>
                               <p class="text-green-500 font-bold">VIEWS</p>
                               <p class="text-gray-500 font-bold text-4xl">2.4K</p>
                           </div>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>

    <!--Articles Display 12-->
    <div class="p-12 ">
        <!--Category 2: Trending on Pangaea-->
        <p class="text-gray-400">Trending on Pangaea</p>
        <div class="grid grid-flow-col grid-cols-3 grid-rows-1 gap-4">
            
            <!--Article 1-->
            <div class="border border-gray-100 rounded-md">
                <img src="\App\assets\img\person working.jpg" alt="" srcset="">
                
                <div class="flex text-gray-500 p-4 m-0 sm:w-8/12 w-full font-bold ">
                    <!--Tag-->
                    <p>Development</p>
                    <!--Date-->
                    <p class="text-right">July 2, 2020</p>
                </div>
                <!--Title-->
               <p class="font-bold">Hybrid vs Nature - Here is how you can chose one over the other</p>
               
               <!--Statistics-->
               <div class="border border-gray-100 rounded-md m-2">
                   <div class="flex space-x-10 ">
                        <p class="text-gray-500">THIS MONTH</p>
                        <a href="http://" class="text-blue-500" >MORE</a>
                   </div>
                   <div class="grid grid-cols-2 divide-x divide-gray-100">
                       <div>
                           <p class="text-green-500 font-bold">KES</p>
                           <p class="text-gray-500 font-bold text-4xl">3489</p>
                       </div>
                       <div>
                           <p class="text-green-500 font-bold">VIEWS</p>
                           <p class="text-gray-500 font-bold text-4xl">2.4K</p>
                       </div>
                   </div>
               </div>
            </div>

            <!--Article 2-->
            <div class="border border-gray-100 rounded-md">
                <img src="\App\assets\img\person working.jpg" alt="" srcset="">
                
                <div class="flex text-gray-500 p-4 m-0 sm:w-8/12 w-full space-x-2 font-bold ">
                    <!--Tag-->
                    <p>Development</p>
                    <!--Date-->
                    <p class="text-right">July 2, 2020</p>
                </div>
                <!--Title-->
               <p class="font-bold">Hybrid vs Nature - Here is how you can chose one over the other</p>
               
               <!--Statistics-->
               <div class="border border-gray-100 rounded-md m-2">
                   <div class="flex space-x-10 ">
                        <p class="text-gray-500">THIS MONTH</p>
                        <a href="http://" class="text-blue-500" >MORE</a>
                   </div>
                   <div class="grid grid-cols-2 divide-x divide-gray-100">
                       <div>
                           <p class="text-green-500 font-bold">KES</p>
                           <p class="text-gray-500 font-bold text-4xl">3489</p>
                       </div>
                       <div>
                           <p class="text-green-500 font-bold">VIEWS</p>
                           <p class="text-gray-500 font-bold text-4xl">2.4K</p>
                       </div>
                   </div>
               </div>
            </div>

            <!--Article 3-->
            <div class="border border-gray-100 rounded-md">
                <img src="\App\assets\img\person working.jpg" alt="" srcset="">
                
                <div class="flex text-gray-500 p-4 m-0 sm:w-8/12 w-full space-x-2 font-bold ">
                    <!--Tag-->
                    <p>Development</p>
                    <!--Date-->
                    <p class="text-right">July 2, 2020</p>
                </div>
                <!--Title-->
               <p class="font-bold">Hybrid vs Nature - Here is how you can chose one over the other</p>
               
               <!--Statistics-->
               <div class="border border-gray-100 rounded-md m-2">
                   <div class="flex space-x-10 ">
                        <p class="text-gray-500">THIS MONTH</p>
                        <a href="http://" class="text-blue-500" >MORE</a>
                   </div>
                   <div class="grid grid-cols-2 divide-x divide-gray-100">
                       <div>
                           <p class="text-green-500 font-bold">KES</p>
                           <p class="text-gray-500 font-bold text-4xl">3489</p>
                       </div>
                       <div>
                           <p class="text-green-500 font-bold">VIEWS</p>
                           <p class="text-gray-500 font-bold text-4xl">2.4K</p>
                       </div>
                   </div>
               </div>
            </div>
        </div>

    </div>

</div>

    
</body>
</html>