<?php

    $mpesaResponse = file_get_contents("php://input");


    // '{"Body":
    //     {"stkCallback":
    //         {"MerchantRequestID":"22945-69436-1",
    //             "CheckoutRequestID":"ws_CO_051220200249322007",
    //             "ResultCode":0,
    //             "ResultDesc":"The service request is processed successfully.",
    //             "CallbackMetadata":
    //                 {"Item":
    //                     [
    //                     {"Name":"Amount","Value":10.00},
    //                     {"Name":"MpesaReceiptNumber","Value":"OL537P9OG7"},
    //                     {"Name":"Balance"},
    //                     {"Name":"TransactionDate","Value":20201205024950},
    //                     {"Name":"PhoneNumber","Value":254740958965}
    //                     ]
    //                 }
    //         }
    //     }
    // }';

    $jsonMpesaResponse = json_decode($mpesaResponse);
    $stkCallBack = $jsonMpesaResponse->Body->stkCallback;
    
    spl_autoload_register(function($classname){
        require_once("../classes/$classname.class.php");
    });

    if($stkCallBack->ResultCode == 0){
    
        $items = $stkCallBack->CallbackMetadata->Item;

        $values = [$stkCallBack->ResultCode,$items[1]->Value, $items[3]->Value , $items[4]->Value, $stkCallBack->MerchantRequestID, $stkCallBack->CheckoutRequestID];
        Utility::updateTable("subscriptionPayment", " resultCode = ?, transactionId = ?,  transactionDate = ?, payer = ?", "merchantId = ? and checkoutRequestId = ?", $values);

        //the subscription status will be updated by Reader constructor
    
    }
    else{
        $values = [$stkCallBack->ResultCode, $stkCallBack->MerchantRequestID, $stkCallBack->CheckoutRequestID];
        Utility::updateTable("subscriptionPayment", "resultCode = ?","merchantId = ? and checkoutRequestId = ?", $values);
    }

exit;

