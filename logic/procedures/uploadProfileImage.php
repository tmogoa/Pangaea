<?php

 require_once("utility.inc.php");

 /**
  * this sets a profile image for a user.
  * the profile image will be saved in storage/profile_images
  */

  if(isset($_FILES['profileImage'])){
      $sentImage = $_FILES['profileImage'];

      if(!Utility::isImage($sentImage['tmp_name'])){
          echo "IE";//image error
          exit;
      }

      $reader = new Reader();
      $reader->setWriterId($_SESSION['userId']);

      $response = Utility::uploadImage($sentImage, "prf-img-". $_SESSION['userId'], "profile_images",true);

      if($response !== false){
          $reader->setProfileImage($response);
          $conn = Utility::makeConnection();
          $reader->uploadImage($response);
          
          echo "{
              success: 1,
              file: \"$response\"
          }";

      }
      else{
          echo "UE";
      }

  }else{
      echo "NISE";//no Image Sent error
  }

?>