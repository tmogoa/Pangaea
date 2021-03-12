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
         * Constants
         */

        const COUNTRIES = array("Afghanistan", "Albania", "Algeria","Andorra",
        "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan",
        "Bahamas", "Bahrain", "Bangladesh","Barbados", "Belarus", "Belgium", "Belize", "Benin",
        "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria",
        "Burkina Faso", "Burundi", "Cote d'Ivoire","Cape-Verde", "Cambodia", "Cameroon", "Canada",
        "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo-Brazzaville", "Congo-DR", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia(Czech Republic)", "Denmark", "Djibouti", "Dominica", "Dominican-Republic", "Ecuador", "Egypt", "El-Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Estonia", "Eswatini(Swaziland)", "Ethiopia", "Fiji", "Finland",
        "France", "Gabon", "Gambia", "Georgia","Germany", "Ghana", "Greece","Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary","Iceland", "India","Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy","Japan", "Jersey", "Jamaica", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo","Kuwait", "Kyrgyzstan", "Laos", "Latvia","Lebanon", "Lesotho", "Liberia", "Lybia", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar(formerly Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania","Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San-Marino", "Sao-Tome-and-Principe", "Saudi-Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon-Islands", "Somalia", "South-Africa", "South-Korea", "South-Sudan", "Spain", "Sri-Lanka", "Sudan", "Suriname", "Sweden", "Switzerland",
        "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste","Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United-Arab-Emirates", "United-Kingdom", "United-States-of-America", "Uruguay", "Uzbekistan", "Vanuatu",
        "Venezuela","Vetican-City", "Vietnam", "Yemen", "Zambia", "Zimbabwe");

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
                return false;
            }
        }

        /**
         * Query the database given a specific table
         * @param $condition
         * do not include the where clause, e.g 'email = ? AND password = ?'
         * @param $values_array is an arroy of the values to be inserted at the place holders.
         * The connection is PDO, so everything is prepared
         */
        public static function queryTable($tableName, $columns, $condition, array $values_array, $connection = null){
            $connectionWasPassed = ($connection == null)?false:true;
            if(!$connectionWasPassed){
                $connection = self::makeConnection();
            }
            $sql = "SELECT $columns from `$tableName` where $condition";
            $stmt = $connection->prepare($sql);
            if($stmt->execute($values_array)){
                $result = $stmt->fetchAll();
                $return = $result;
            }
            else{
                $return = false;
            }

            if(!$connectionWasPassed){
                $connection = null;
            }

            return $return;
        }

        /**
         * Insert into a table and returns the ID of the row affected if its auto_increment or manually added.
         * @param $values_specs 
         * the values of the specfied columns eg. '?, ?, ?, ?'
         * @param $columns_specification
         * The columns to insert values for. e.g. email, password, x, y
         * @param array $values to be inserted into the table. Order matters
         * 
         */

         public static function insertIntoTable($tableName, $columns_specification, $values_specs, array $values, $connection = null){
            $connectionWasPassed = ($connection == null)?false:true;
            if(!$connectionWasPassed){
                $connection = self::makeConnection();
            }
            
            $sql = "INSERT into `$tableName`($columns_specification) values ($values_specs)";
            $stmt = $connection->prepare($sql);

            if($stmt->execute($values)){
                $return = $connection->lastInsertId();
            }else{
                $return = false;
            }

            if(!$connectionWasPassed){
                $connection = null;
            }

            return $return;
         }

        /**
         * updates a table in the database. Please put ticks around sql keywords like `order` if 
         * it will be used as a column name or a value. don't put ticks on table names. that is already handled by default.
         * @param $tableName  the name of the table to be updated
         * @param $columns_specs  the columns to be updated. E.g. "name = ?, order=?"
         * @param $condition  the logic in the WHERE clause. e.g. "userId = ?"
         * @param array $values  the values for the place holder in the $columns_specs and $condition
         * This method uses PDO under the hood, therefore, order of the values matter.
         */
          public function updateTable($tableName, $columns_specs, $condition, array $values, PDO $connection = null){
                $connectionWasPassed = ($connection == null)?false:true;
                if(!$connectionWasPassed){
                    $connection = self::makeConnection();
                }
                
                $sql = "UPDATE `$tableName` set $columns_specs where $condition";
                $stmt = $connection->prepare($sql);

                if($stmt->execute($values)){
                    $return = true;
                }else{
                    $return = false;
                }

                if(!$connectionWasPassed){
                    $connection = null;
                }

                return $return;
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

         /**
          * Checks if an email exist already
          */
          public static function doesEmailExist($email){
              $table = "";
              $columns = "";
              $condition = "";
              $values = [$email];
              $connection = self::makeConnection();

              if(self::queryTable($table, $columns, $condition, $values, $connection)){
                  return true;
              }

              return false;
          }

          /**
           * Checks if the password is a qualified password
           * @return PNE|PLLE|PULE|PLSE
           * Password Number Error: Password must include numbers
           * Password Lowercase Letter Error: Password must include lowercase letters
           * Password Uppercase Letter Error: Password must include uppercase letters
           * Password Length Short Error: Password must length is shorter then 9 characters.
           */

           public static function isPasswordStrong($password){
            if(strlen($password) >= 9){
                if(preg_match('@[A-Z]@', $password)){
                   if(preg_match('@[a-z]@', $password)){
                      if(preg_match('@[0-9]@', $password)){
                        return true;
                      }
                      else{
                        return "PNE";//Password Number Error
                      }
                   }else{
                     return "PLLE";//Password Lowercase Letter Error
                   }
                }
                else{
                  return "PULE";//Password Uppercase Letter Errors
                }
             }
             else{
               return "PLSE";//Password Length Short Error
             }
           }

           /**
            * Checks the nationality of the user to ensure that it was selected from the list and not sent through the console.
            */

            public static function checkCountry($country){
                if(!in_array($country, self::COUNTRIES)){
                    return false;
                }

                return true;
            }

            public static function sendVerificationEmail($email){
                //Send email verification
            }


    }

?>