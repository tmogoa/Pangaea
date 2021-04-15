<?php

    spl_autoload_register(function($name){
        require_once("../classes/$name.class.php");
    });


    $writer = new writer();

    $email = isset($_POST['email'])?filter_var($_POST['email'], FILTER_SANITIZE_STRING):"";
    $password = isset($_POST['email'])?filter_var($_POST['password'], FILTER_SANITIZE_STRING):"";
    $writer->setEmail($email);
    $writer->setPassword($password);

    echo $writer->login();

?>
