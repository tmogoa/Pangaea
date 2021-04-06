<?php
    require_once("../classes/utility.class.php");
    require_once("../classes/writer.class.php");

    //to signup, we need the email and the password
    $email = isset($_POST['email'])?$_POST['email']:"";
    $password = isset($_POST['password'])?$_POST['password']:"";

    $newUser = new Writer();

    $newUser->setEmail($email);
    $newUser->setPassword($password);

    $conn = Utility::makeConnection();
    echo $newUser->register($conn);

?>