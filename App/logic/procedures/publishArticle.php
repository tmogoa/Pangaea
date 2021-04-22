<?php
 require_once("utility.inc.php");

 /** 
  * Before this script is called, make sure that the items in the article have been saved.
  * We will check for tags and add them before going live.
  */

    $articleId = isset($_POST['id']) ? (int)$_POST['id'] :"";
    
    if( $articleId == 0 || !is_int($articleId) ){
        echo "NIE";//no id error
        exit;
    }

    $article = new Article();
    $article->setId($articleId);

    //get the article title
    //Most of the validation of the input is done in the setters and getters
    $title = isset($_POST['title'])?filter_var($_POST['title'], FILTER_SANITIZE_STRING):"";
    $article->setTitle($title);

    $subtitle = isset($_POST['subtitle'])?filter_var($_POST['subtitle'], FILTER_SANITIZE_STRING):"";
    $article->setSubtitle($subtitle);

    $body = isset($_POST['body']) ? json_encode($_POST['body']) : "";
    $article->setBody($body);
    
    //we expect the tags to be a JSON array
    $tags = isset($_POST['tags'])?$_POST['tags']:"";
    $article->setTags(json_decode($tags));

    //This changes the publish status of the article in the database
    $article->persist();

    if($article->publish()){
        echo "OK";
    }else{
        echo "UE";//unknown error occurred
    }

?>