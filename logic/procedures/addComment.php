<?php
require_once("utility.inc.php");

// Article ID needs to exist, this is used to determine which comments are for which article
if (isset($_POST['articleId'])) {
    // Check if the submitted form variables exist
    if(!isset($_POST['comment'])){
        echo "NCE";//No comment Error
        exit;
    }

    if( Utility::insertIntoTable("comment", "articleId, readerId, comment", "?, ?, ?", [$_GET['articleId'], $_SESSION['userId'], $_POST['comment']])){
        exit('OK');
    }else{
        echo "UE";//Uknown error
    }
    // Get all comments by the Article ID ordered by the submit date
} else {
    exit('NIE'); //No Id Errors
}
