<?php
/**
 * The utility class contains all the function in that would normally exist in the functions.php
 * Every field in this class is static and functions are also static.
 */
    class Utility{
        /**
         * For database connections
         */
        public static $dbName = "";
        public static $dbServerName = "";
        public static $dbUserName = "";
        public static $dbPassword = "";
        /**
         * Regular expressions for input validations
         */
        public static $nameRegex = "";
        public static $phoneRegex = "";  
        public static $textAreaRegex = "";
        
        /**
         * Makes a connection to the database and returns the connection object.
         * @param $options 
         * Pass the array of options if you want to set options.
         * The default options = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC ]
         */
        public static function makeConnection($options = false){
            $dsn = "mysql:host=". self::$dbServerName. ";dbname=". self::$dbName;
            if(!$options){
                $options = [ 
                    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
             ];
            }

            try{

                $pdo_conn = new PDO($dsn, self::$dbUserName, self::$dbPassword, $options);
                return $pdo_conn;
            }
            catch(PDOException $ex){
                echo "{$ex->getMessage()}";
                return null;
            }
        }


        /**
         * checks names to ensure that they meet policy
         */
        public static function checkName($name){
            if(preg_match(self::$nameRegex, $name)){
                return true;
            }
            return false;
        }

        /**
         * Checks the phone number against the legal regular expression for phone numbers
         */
        public static function checkPhone($phoneNumber){
            if(preg_match(self::$phoneRegex, $phoneNumber)){
                return true;
            }

            return false;
        }

        /**
         * checks the textarea input 
         */
        public static function checkTextAreaInput($textAreaInput){
            if(preg_match(self::$textAreaRegex, $textAreaInput)){
                return true;
            }
            return false;
        }

        /**
         * Checks the editor's input
         */

         public static function checkEditorInput($editorInput){
             $inputWithoutTags = strip_tags($editorInput);
             if(self::checkTextAreaInput($inputWithoutTags)){
                 return true;
             }
             return false;
         }
        
         /**
          * Checks to verify that the email meets the requirement
          */
         public static function checkEmail($email){
             if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                return true;
             }
             return false;
         }

         
    }

?>