<?php

    /**
     * This function contains all the methods for mpesa operation
     */

    class Mpesa{
        public static $headers = ["Content-Type:application/json; charset=utf8"];
        public static $consumerKey = "f9BnUWpuAoIAUCfOZnDO9UxQT6UofnAa";
        public static $consumerSecret = "KYqsHbORv6lTiHBJ";
        public static $till = "174379";
        public static $shortCode1 = "603021";
        public static $shortCode2 = "600000";
        public static $initiatorName = "apiop37";
        public static $SecurityCredential = "Cgeq4Eu8loJDWnx5BMkbZdqm7TgpdgLQeuhi6qCsHw8e/3FPsbJO73ZCDJmEUbVdfpPM+OOAh1Yx6IPk8KvAKWGTw30gjgA2HgLid6Bwlk0vhbY3/YcuVprTgpcHer2KZsFJwJGwRfODDVDYzqeM/eceuC86RBh3G5czNft8Pm9af/4W1kW13O9D/q+h6/13dqWrvJlzmo4JjU6CfVKFYUKNNNXjVztE5BjW5TUehqU0KLggkkuUl6jjIgoqFFt3bo+v7D0QcY1rbNaUCDZQ5wjzx+Al+B3XsYMtQgNrAmejb8KCwrdFow6gPmyep0nqO7VMyFL8td8ZCpZ7l3k8Zw==";
        public static $organizationType = 4; //operating on a till number
        public static $passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        public static $testNumber = "254708374149";
    
    
        //urls
        
        //till stk push
        public static $till_call_back_url = "https://localhost/pangaea/logic/procedures/mpesaTillResponse.php";
    
        //account ballance
        public static $account_bal_result_url = ""; 
        public static $account_bal_timeout_url = "";
    
        //b2b
        public static $b2b_result_url = "";
        public static $b2b_timeout_url = "";
    
        //b2c
        public static $b2c_result_url = "";
        public static $b2c_timeout_url = "";
     
        //reversal
        public static $reversal_result_url = "";
        public static $reversal_timeout_url = "";
    
    
        /**
         * Generate and return Access token
         */
        public static function generateToken(){
            $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, self::$headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_USERPWD, self::$consumerKey.":".self::$consumerSecret);
    
            $result = curl_exec($curl);
    
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            //get access token
            if($status == 200){
                $result = json_decode($result);
    
                $access_token = $result->access_token;
            
                return $access_token;
            }
    
            return false;
        }
    
        /**
         * Allows the reader to pay their bills through stk push
         */
        public static function stkPush($phone, $amount){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
      
            $access_token = self::generateToken();
            if($access_token === false){
                return false;
            }
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header
            
            $timestamp = date("YmdHis");
            $commandID = 'CustomerPayBillOnline';
            //echo $timestamp;
            $password = base64_encode(self::$till.self::$passkey.$timestamp);
            $curl_post_data = array(
              'BusinessShortCode' => self::$till,
              'Password' => $password,
              'Timestamp' => $timestamp,
              'TransactionType' => $commandID,
              'Amount' => $amount,
              'PartyA' => $phone,
              'PartyB' => self::$till,
              'PhoneNumber' => $phone,
              'CallBackURL' => self::$till_call_back_url,
              'AccountReference' => "Carata Cart",
              'TransactionDesc' => 'Pay for order'
            );
            
            $data_string = json_encode($curl_post_data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string); 
            $curl_response = curl_exec($curl);
            curl_close($curl);
    
            $response = json_decode($curl_response);
    
            //var_dump($response);
            if(isset($response->ResponseCode) && $response->ResponseCode == "0"){
              //saving this in the order table
              /**
               *  - subPaymentId, readerId, merchantId (varchar 500), checkoutRequestId varchar(500), payer (varchar(20)), transactionId varchar(255), transactionDate timestamp, resultCode (int default -1), month, year
               */
              $tableName = "subscriptionPayment";
              $column_specs = "readerId, merchantId, checkoutRequestId, `month`, `year`";
              $value_specs = "?,?,?,?,?";
              $values= [$_SESSION['userId'], $response->MerchantRequestID, $response->CheckoutRequestID, date("F"), date("Y")];

              Utility::insertIntoTable($tableName, $column_specs, $value_specs, $values);
              $return = true;

            }else
            {
              $return = false;
            }
    
            return $return;
        }
    
         /**
          * Purchase from a phone number
          */
         public static function B2C($phoneNumber, $amount){
          $url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
      
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '. self::generateToken())); //setting custom header
          
          
          $curl_post_data = array(
            //Fill in the request parameters with valid values
            'InitiatorName' => self::$initiatorName,
            'SecurityCredential' => self::$SecurityCredential,
            'CommandID' => 'BusinessPayment',
            'Amount' => $amount,
            'PartyA' => self::$shortCode1,
            'PartyB' => $phoneNumber,
            'Remarks' => 'Purchase of items for Carata orders',
            'QueueTimeOutURL' => self::$b2c_timeout_url,
            'ResultURL' => self::$b2c_result_url,
            'Occasion' => 'Purchasing from phone number'
          );
          
          $data_string = json_encode($curl_post_data);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
          
          $curl_response = curl_exec($curl);
          curl_close($curl);
    
          $response = json_decode($curl_response);
    
          if(isset($response->errorCode)){
            return "EAG";//Error Accessing gateway
          }
          else {
            //insert the Ids into the response table. 
            //For the transaction Id, since it is the primary key, it will be the conversation
            //id util it is updated from the B2C result page.
            $tableName = "";
            $column_specs = "originatorConversationId, conversationId, transactionId, receiver, amount";
            $value_specs = "?,?,?,?,?";
            $values = [$response->OriginatorConversationID, $response->ConversationID, $response->ConversationID, $phoneNumber, $amount];
                      
            
            if(Utility::insertIntoTable($tableName, $column_specs, $value_specs, $values)){
              $return = [$response->ConversationID, $response->OriginatorConversationID];
            }else{
              $return = "EAD";//error accessing database
            }
            return $return;
          }
          
    
          return "UEO";//Unknown error occurred
         }
    
         /**
          * Make reversal when a customer pays
          */
         public static function makeReversal($transactionID, $amount){
          $url = 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
      
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '. self::generateToken())); //setting custom header
          
          
          $curl_post_data = array(
            //Fill in the request parameters with valid values
            'Initiator' => self::$initiatorName,
            'SecurityCredential' => self::$SecurityCredential,
            'CommandID' => 'TransactionReversal',
            'TransactionID' => $transactionID,
            'Amount' => $amount,
            'ReceiverParty' => self::$shortCode1,
            'RecieverIdentifierType' => '11',
            'ResultURL' => self::$reversal_result_url,
            'QueueTimeOutURL' => self::$reversal_timeout_url,
            'Remarks' => 'Cancelling Order',
            'Occasion' => 'Canceling an order'
          );
          
          $data_string = json_encode($curl_post_data);
          
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
          
          $curl_response = curl_exec($curl);
          curl_close($curl);
    
          $response = json_decode($curl_response);
    
          if(isset($response->errorCode)){
            return "EAG";//Error Accessing gateway
          }
          else{

            $tableName = "";
            $column_specs = "transactionId, originatorConversationId, conversationId, originalTransactionId";
            $value_specs = "?, ?, ?, ?";
            $values = [$response->ConversationID, $response->OriginatorConversationID, $response->ConversationID, $transactionID];

            Utility::insertIntoTable($tableName, $column_specs, $value_specs, $values);

            return true;
          }
    
    
          return "UEO";//Unknown error occurred
        }
    
     }

?>