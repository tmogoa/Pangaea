<?php
    session_start();

    if(!isset($_SESSION['user_id'])){
        header("Location: ../../login.php");
    }

    spl_autoload_register(function($name){
        $name = strtolower($name);
        require_once("../classes/$name.class.php");
    });

    /**
     * Editing a user profile
     * We will only require the the email and password be set. Any other that is added by the 
     * user is under their control.
     * We will look for new password, first name, last name, phone number- includes country code, nationality.
     * If the user sents an image, then we will check the image. We will send back the appropriate 
     * error messages. however, the image will be uploaded after the database is updated.
     * User profile image will be save with the prefix profile-img-userId-uniqueid().jpeg
     */

     $user = new Writer();
     $changePassword = false;
    
     $user->setWriterId($_SESSION['userr_id']);

     //do not worry about validating the input. Everything is done by the persist method in the Writer
     //class.

    if(isset($_POST['firstname'])){
        $user->setFirstName($_POST['firstname']);
    }

    if(isset($_POST['lastname'])){
        $user->setLastName($_POST['lastname']);
    }

    if(isset($_POST['email'])){
        if(!empty($_POST['email'])){
            $user->setEmail($_POST['email']);
        }
        
    }

    if(isset($_POST['phone-number'])){
        $user->setPhoneNumber($_POST['phone-number']);
    }

    if(isset($_POST['country'])){
        $user->setNationality($_POST['nationality']);
    }

    if(isset($_POST['new-password'])){
        //the old password must also be sent. Else, we will not honor the change request.
        if(!empty($_POST['old-password'])){
            //then proceed to make sure the password match.
            $tableName = "user";
            $column_specs = "password";
            $condition = "userId = ?";
            $values = [$user->getWriterId()];
            $password_result = Utility::queryTable($tableName, $column_specs, $condition, $values);
            
            if($password_result){
                $password = $password_result[0];
                //This is the hashed password
                $user->setPassword($password);
                $changePassword = true;
            }else{
                echo "SQLE";//a database error occured while trying to change the password
                exit;
            }
        }
        else{
            echo "OPNSE";//old password not set error
            exit;
        }
    }

    //if the change password is true, then we will try to update the password first.

    if($changePassword){
        //change the password
        $response = $user->changePassword($_POST['old-password'], $_POST['new-password']);
    }

    if($changePassword && $response !== true){
        echo $response;
        exit;
    }

    //go ahead to persist the user to the database if all goes well
    $conn = Utility::makeConnection();
    echo $user->persist($conn);

    //if the image contains an error, we will not upload it and not error message will be sent back to the user.

    if(isset($_FILES['profile-picture'])){
        //the user wants to upload their profile image
        if(Utility::isImage($_FILES['profile-picture']['tmp_name'])){
            //proceed to upload the image
            Utility::uploadImage($_FILES['profile-picture'], "profile-img-". $user->getWriterId()."-".uniqid(), true);
        }
    }

?>