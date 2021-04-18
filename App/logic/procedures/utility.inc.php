<?php

    session_start();

    if(!isset($_SESSION['user_id'])){
        header("Location: ../../login.php");
    }

    spl_autoload_register(function($name){
        $name = strtolower($name);
        require_once("../classes/$name.class.php");
    });

?>