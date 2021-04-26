<?php

    /**
     * This script calculates the earnings for each article and populate the earning table.
     * The earnings table will be used by the calculateTotalEarning method
     *  and calculateEarningsForArticle method in the writers class. 
     * This script should not be called by the browser as it is to be done by a cron job that 
     * runs at the end of the month. 
     */

     spl_autoload_register(function($classname){
         $classname = strtolower($classname);
         require_once("../logic/classes/$classname.class.php");
     });

     /**
      * Calculate earnings per month basis
      */
      $conn = Utility::makeConnection();

      $tableName = "users";
      $columns = "userId";
      $condition = "1 = ?";
      $values = [1];

      $allReaders = Utility::queryTable($tableName, $columns, $condition, $values, $conn);
      
      if($allReaders){

          foreach($allReaders as $readerId){
            $reader = new Reader($readerId);
            if($reader->isSubscribed()){
                //proceed to calculate the month payment
            }
          }

      }
?>