<?php
    require_once("utility.inc.php");
/**
 * This script sets the data collected about an article such as the time spent reading
 * the article
 */

 if(!isset($_POST['id'])){
     echo "NAI";//no article Id;
     exit;
 }

 $articleId = (int)$_POST['id'];
 if($articleId == 0){
     echo "NIA";//no id error
     exit;
 }

 $article = new Article($articleId);

 if($article->getWriterId() == $_SESSION['userId']){
     echo "AWE";//Article Writer Error
 }
 $readTime = isset($_POST['readTime'])?$_POST['readTime']:1; //at least 1 second read time

 $reader = new Reader();
 $reader->setWriterId($_SESSION['userId']);

 //updated the readTime of the article
 echo $reader->read($articleId, $readTime);
?>