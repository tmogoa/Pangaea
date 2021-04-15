<?php
 session_start();

 spl_autoload_register(function($name){
    $name = strtolower($name);
    require_once("../classes/$name.class.php");
 });

 $writer = new Writer();
 $writer->logout();
 
?>