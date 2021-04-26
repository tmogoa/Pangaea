<?php

    /**
     * This script accepts the command from the reader to pay their subscription bills
     */

     require_once("utility.inc.php");

     if(!isset($_POST['action'])){
         echo "NAE";//no action error
         exit;
     }

     $action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);
     $reader = new Reader($_SESSION['userId']);
     if($reader->isSubscribed()){
         echo 'yes'; //already paid for this month
         exit;
     }
     switch($action){
         case 'pay':
            {
                $response = $reader->paySubscriptionFee();
                if($response === true){
                    echo "OK";
                }
                else if($response === false){
                    echo "UE";
                }else{
                    echo $response;
                }
                break;
            }
         case "confirm":
            {
                if($reader->hasPaid()){
                    echo "yes";
                }
                else{
                    echo "no";
                }
                break;
            }
         default:
          {
              echo "IAE";//invalid action Error
          }
     }

?>