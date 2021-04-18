<?php
//include("../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
/**
 * The utility class contains all the function in that would normally exist in the functions.php
 * Every field in this class is static and functions are also static.
 */
    class Utility{
        /**
         * For database connections
         */
        public static $dbName = "pangaea_db";
        public static $dbServerName = "127.0.0.1";
        public static $dbUserName = "root";
        public static $dbPassword = "";
        /**
         * Regular expressions for input validations
         */
        public static $nameRegex = "/^[\w]+(\s?[\w\-_\'\.]+?\s*?)+?$/";
        public static $phoneRegex = "/^\+\d{10,15}$/";  
        public static $textAreaRegex = ""; //who cares for sql injection when we are using PDO???
        
        /**
         * Constants
         */
        const STOPWORDS = array("i", "me", "my", "myself", "we", "our", "ours", "ourselves", "you", "your", "yours", "yourself", "yourselves", "he", "him", "his", "himself", "she", "her", "hers", "herself", "it", "its", "itself", "they", "them", "their", "theirs", "themselves", "what", "which", "who", "whom", "this", "that", "these", "those", "am", "is", "are", "was", "were", "be", "been", "being", "have", "has", "had", "having", "do", "does", "did", "doing", "a", "an", "the", "and", "but", "if", "or", "because", "as", "until", "while", "of", "at", "by", "for", "with", "about", "against", "between", "into", "through", "during", "before", "after", "above", "below", "to", "from", "up", "down", "in", "out", "on", "off", "over", "under", "again", "further", "then", "once", "here", "there", "when", "where", "why", "how", "all", "any", "both", "each", "few", "more", "most", "other", "some", "such", "no", "nor", "not", "only", "own", "near" ,"same", "so", "than", "too", "very", "s", "t", "can", "will", "just", "don", "should", "now");

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
         * @param $values_array is an array of the values to be inserted at the place holders.
         * The connection is PDO, so everything is prepared
         * The table name is treated as an sql keyword. That means, it already has the back ticks around 
         * it.
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
         * This uses PDO under the hood.
         * updates a table in the database. Please put ticks around sql keywords like `order` if 
         * they are used as a column name or a value. don't put ticks on table names. that is already handled by default.
         * @param $tableName  the name of the table to be updated
         * @param $columns_specs  the columns to be updated. E.g. "name = ?, order=?"
         * @param $condition  the logic in the WHERE clause. e.g. "userId = ?"
         * @param array $values  the values for the place holder in the $columns_specs and $condition
         * This method uses PDO under the hood, therefore, order of the values matter.
         */
          public static function updateTable($tableName, $columns_specs, $condition, array $values, PDO $connection = null){
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
           * Delete from table. 
           */
          public static function deleteFromTable($tableName, $condition, array $values, PDO $connection = null){
            $connectionWasPassed = ($connection == null)?false:true;
            if(!$connectionWasPassed){
                $connection = self::makeConnection();
            }
            
            $sql = "DELETE FROM `$tableName` where $condition";
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
        public static function sanitizeTextEditorInput($textEditorInput){
            return htmlspecialchars($textEditorInput);
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
              $table = "users";
              $columns = "email";
              $condition = "email = ?";
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

            /**
             * This function sends an email to a user to verify their email.
             * 
             */
            public static function sendVerificationEmail($email){
                //Send email verification
                //we send the hashed email, the id and the date of signup

            }

            /**
             * This function checks HTTP requests
             */
            public static function verifyHttpRequest($request){
                $request = $_SERVER['REQUEST'];
            }
    
            /**
             * This function allows the uploading of images.
             * It takes the Image Array, the name of the image and the directory to place the image in.
             * When an image is given a name, the name is appended with a -uniqueId to make the image 
             * name unique. For example, a name Levi, after upload will be Levi-123a3bc4567c2.jpeg. 
             * All images are saved as a jpeg format. To retrieve an image, please use the returnImgSrc
             * function to give you the image source. This is because the unique Id after the image has
             * been uploaded makes it impossible to fetch it directly.
             * @param array $image - The image array from $_FILES
             * @param string $save_name - The name to save the image with
             * @param string $in_directory - The directory in which the image should be saved.
             * The directory will be autoloaded. So you don't have to worry about the ../. hehe. However
             * It must be in the storage directory.
             * @param bool $update = false. If you are updating a currently existing image, then
             * set this parameter to true. It will allow the method to delete the previously existing 
             * image and upload the new one.
             * 
             * @return bool
             */

             public static function uploadImage(array $image, $save_name, $in_directory, $update = false){
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $ext = strtolower($ext);

                //if we are updating an image, we will go ahead and delete the previously existing one.
                if($update){
                    $oldImage = "../".self::returnImageFullName($save_name, $in_directory);
                    if(file_exists($oldImage) && $oldImage != "../"){
                        unlink("$oldImage");
                    }  
                }
                switch (exif_imagetype($image['tmp_name'])) {
                    case IMAGETYPE_PNG:
                        $imageTmp=imagecreatefrompng($image['tmp_name']);
                        break;
                    case IMAGETYPE_JPEG:
                        $imageTmp=imagecreatefromjpeg($image['tmp_name']);
                        break;
                    case IMAGETYPE_GIF:
                        $imageTmp=imagecreatefromgif($image['tmp_name']);
                        break;
                    case IMAGETYPE_BMP:
                        $imageTmp=imagecreatefrombmp($image['tmp_name']);
                        break;
                    // Defaults to JPG
                    default:
                        $imageTmp=imagecreatefromjpeg($image['tmp_name']);
                        break;
                }
            
                // quality is a value from 0 (worst) to 100 (best)
                
                if(imagejpeg($imageTmp, "../../storage/".$in_directory."/".$save_name."-".uniqid().".jpeg", 70)){
                    imagedestroy($imageTmp);
                    return true;
                }
                else{
                    imagedestroy($imageTmp);
                    return false;
                }
            }

            /**
             * Returns the full name of the image whose name is passed by the image name parameter.
             * The directory of the image is also passed to the function so that we check the right directory. Please note that all images is stored in the storage folder and that is 
             * where we are going to check.
             * @param string $image_name the name of the image whose full name is to be returned.
             * @param string $in_directory the directory in which we should search.
             * 
             * @return string The full name of the image. for example, image name Levi will be returne as 
             * Levi-123a4e56f3c766.jpeg 
             */

             public static function returnImageFullName($image_name, $in_directory){
                $target_dir = "../../storage/$in_directory";
                $all_files  = glob("$target_dir/$image_name-*.jpeg")[0];
                $file_name = explode("/", $all_files);
                $file_name = $file_name[count($file_name) - 1];
                return $file_name;
             }

            /**
             * Checks if an image is in an acceptable format.
             * The extensions are .jpg, .jpeg, .png, .bmp, .webp 
             * @param $path the path to the image. Usually it is the tmp_name in the $_FILES
             * @return bool
             */
             public static function isImage($path){
                $check = getimagesize($path);
                if(in_array($check[2], array('jpg', IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP, IMAGETYPE_WEBP))){
                    return true;
                }
                return false;
            }

            /**
             * This function removes stop words from the keywords
             */

             public static function removeStopwords(array $inputArray){
                 $inputArray = array_map("strtolower", $inputArray);
                 //return the difference in intersection between the two arrays
                 return array_diff($inputArray, self::STOPWORDS);
             }



    }

?>