<?php

 /**
  * This script inserts a new article into the database and returns the Id of the new article
  * If the article cannot be inserted into the database, the page will exit.
  */

  require_once (__DIR__."/logic/classes/utility.class.php");

  $userId = $_SESSION['userId'];
  
  $tableName = "article";
  $column_spec = "writerId";
  $values_spec = "?";

  $articleId = Utility::insertIntoTable($tableName, $column_spec, $values_spec, [$userId]);

  if($articleId == false){
      //and error occurred
    exit;
  }

?>