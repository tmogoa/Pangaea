<?php

    /**
     * This script accepts the command from the reader to pay their subscription bills
     * Responses: NPNE: No Phone Number Error - Redirects the user to the edit profile page
     * to add their phone.
     * UE: unknown Error
     * OK: stkPush made successfully
     * YES: confirmed payment
     * NO: payment was not made
     * IAE: Invalid Action Error
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
                    echo "YES";
                }
                else{
                    echo "NO";
                }
                break;
            }
         default:
          {
              echo "IAE";//invalid action Error
          }
     }

?>