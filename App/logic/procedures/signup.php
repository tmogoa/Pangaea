<?php
    spl_autoload_register(function($name){
        require_once("../classes/$name.php");
    });

 
    //to signup, we need the email and the password
    /**
     * This procedure is totally not bounded to a front end.
     * We verify the origin of the message before allowing the operation.
     */
    $email = isset($_POST['email'])?$_POST['email']:"";
    $password = isset($_POST['password'])?$_POST['password']:"";

    $newUser = new Writer();

    $newUser->setEmail($email);
    $newUser->setPassword($password);

    $conn = Utility::makeConnection();
    echo $newUser->register($conn);

?>