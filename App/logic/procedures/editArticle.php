<?php
    require_once("utility.inc.php");
    /**
     * Feature images are stored in the database tmporary-feature image table. 
     * When the add article or persist article request is made, we expect the featured image url to
     * be sent. If the feature image url is not sent, then we proceed to take the first image in the
     * article to be the featured image.
     */

    $articleId = 

    $article = new Article();

    //get the article title
    //Most of the validation of the input is done in the setters and getters
    $title = isset($_POST['title'])?filter_var($_POST['title'], FILTER_SANITIZE_STRING):"";
    $article->setTitle($title);

    $subtitle = isset($_POST['subtitle'])?filter_var($_POST['subtitle'], FILTER_SANITIZE_STRING):"";
    $article->setSubtitle($subtitle);

    $body = isset($_POST['body'])?$_POST['body']:"";

    //we expect the tags to be a JSON array
    $tags = isset($_POST['tags'])?$_POST['tags']:"";
    $article->setTags(json_decode($tags));

    echo $article->addArticle($_SESSION['userId']);

    //feature image should be sent here as the [path, id] JSON ofcourse
    $featureImage = isset($_POST['featureImg-data'])?filter_var($_POST['img'], FILTER_SANITIZE_STRING):"";
    
    $tmpImgId = json_decode($featureImage)[1];
    $featureImage = json_decode($featureImage)[1];
    $article->setFeaturedImage($featureImage, $tmpImgId);
    
?>