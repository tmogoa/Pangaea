<?php
  require_once("utility.inc.php");

  /**
   * This script provides a list of suggested tags to the user based on the user input
   */

   $input = isset($_GET['tagInput'])?filter_var($_GET['tagInput'], FILTER_SANITIZE_STRING):exit;

   //suggest 10 tags

   $suggestedTags = Utility::queryTable("articleTopics", "aTopicId, topic", "topic LIKE ? order by topic LIMIT 0, 10", ["%$input%"]);// removed bug here

  $suggestions = [];
  if($suggestedTags){
    foreach($suggestedTags as $suggestedTag){
      $tag = new Tag();
      $tag->id = $suggestedTag['aTopicId'];
      $tag->text = $suggestedTag['topic'];
      $suggestions[] = $tag;//removed bug here
    }
  }// removed else block

  echo json_encode($suggestions);
  
?>