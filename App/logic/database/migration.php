<?php
    //auto load classes
    spl_autoload_register(function($name){
        require_once("../classes/$name.class.php");
    });

    class DatabaseCreator{

        /**
         * Write the transaction that will create the tables in this function
         */
        public static function makeDatabase(){
            /**
             * Please make sure to set your database name in the .env file.
             */
            $conn = Utility::makeConnection();

            try{
                $conn->beginTransaction();

                //Abart, please write the db creation statement in here. Make sure to add drop if exist or something to check if the table already exist if you only want to update it.

                $conn->commit();
            }
            catch(Exception $e){
                $conn->rollBack();
            }

        }
    }

?>