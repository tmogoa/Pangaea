<?php
    spl_autoload_register(function($name){
        require_once("../classes/$name.class.php");
    });

 
    //to signup, we need the email and the password
    /**
     * This procedure is totally not bounded to a front end.
     * We verify the origin of the message before allowing the operation.
     */
    $email = isset($_POST['email'])?$_POST['email']:"";
    $password = isset($_POST['password'])?$_POST['password']:"";

    $newUser = new writer();

    $newUser->setEmail($email);
    $newUser->setPassword($password);

    $conn = utility::makeConnection();
    echo $newUser->register($conn);

?>