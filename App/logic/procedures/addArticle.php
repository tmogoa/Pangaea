<?php

 /**
  * This script inserts a new article into the database and returns the Id of the new article
  * If the article cannot be inserted into the database, the page will exit.
  */
  require_once (getcwd() . "/logic/classes/utility.class.php");

  $userId = $_SESSION['userId'];
  
  $tableName = "article";
  $column_spec = "writerId, publishStatus, featured_image ";
  $values_spec = "?, ?, ?";

  $articleId = Utility::insertIntoTable($tableName, $column_spec, $values_spec, [$userId, "draft", "assets/img/logo.svg"]);
  $_GET['id'] = $articleId;

  if($articleId == false){
      //and error occurred
    exit;
  }

?>