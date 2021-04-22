<?php
 require_once("utility.inc.php");

 /**
  * This script applauds an article. The applaud is toggle (clap and unclap)
  */
    $articleId = isset($_POST['id']) ? (int)$_POST['id'] :"";
    
    if( $articleId == 0 || !is_int($articleId) ){
        echo "NIE";//no id error
        exit;
    }

    $article = new Article();
    $article->setId($articleId);

    $response = $article->applaud($_SESSION['userId']);

    if($response){
        echo "OK";
    }else{
        echo "UE";//unknown error occurred
    }

?>