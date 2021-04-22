<?php
    
    require_once("utility.inc.php");
    /**
     * Feature images are stored in the database tmporary-feature image table. 
     * When the add article or persist article request is made, we expect the featured image url to
     * be sent. If the feature image url is not sent, then we proceed to take the first image in the
     * article to be the featured image.
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
    // $tags = isset($_POST['tags'])?$_POST['tags']:"";
    // $article->setTags(json_decode($tags));

    echo $article->persist();

    //feature image should be sent here as the [path, id] JSON ofcourse
    $featureImage = isset($_POST['featureImg'])?filter_var($_POST['featureImg'], FILTER_SANITIZE_STRING):"";
    
    $article->setFeaturedImage($featureImage, $tmpImgId);
    
?>