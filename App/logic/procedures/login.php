<?php

    spl_autoload_register(function($name){
        require_once("../classes/$name.class.php");
    });

    
    $writer = new Writer();


?>
