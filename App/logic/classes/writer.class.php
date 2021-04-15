<?php

/**
 * Writer class. This class is the super class for the Reader class.
 * Registration of a new user happens through this class.
 */
 class Writer{
    protected $writerId, $firstName, $lastName, $email = null, $password = null, $phoneNumber, $nationality;
    /**
     * all the articles that a writer has written.
     */
    protected $aritcles = [];

    /**
     * Creates a writer object with no field set.
     * Every field must be set, including the Id.
     * If you want to create a writer from the database, call
     * Writer(writerId: int); pass the connection in the second parameter if you already
     * have a connection to use.
     */
    public function __construct($writerId = false, &$conn = null){
        if($writerId){
            $connectionWasPassed = ($conn == null)?false:true;
            if(!$connectionWasPassed){
                $conn = Utility::makeConnection();
            }
            //todo 
            $tableName = "user";
            $column_specs = "*";
            $condition = "userId = ?";
            $values = [$writerId];
            $details =  Utility::queryTable($tableName, $column_specs, $condition, $values, $conn);
            $this->firstName = $details[0]['firstName'];
            $this->lastName = $details[0]['lastName'];
            $this->email = $details[0]['email'];
            $this->password = $details[0]['password'];

            if(!$connectionWasPassed){
                $conn = null;
            }
        }
    }

    /**
     * Registers a new user.
     * Before this function is called, these fields must be set: email, password. All other fields can be
     * updated using the edit profile module.
     * @return NEE|NPE|UEE|UPE|ADE|SQE|EEE
     * NEE: Null Email Error, NPE: Null password Error, UEE: Unqualified email error, UPE: Unqualified
     * Password Error, ADE: Accessing Database Error, SQE: Sql query error, EEE: Email exist error
     * @return PNE|PLLE|PULE|PLSE
     *   Password Number Error: Password must include numbers
     *  Password Lowercase Letter Error: Password must include lowercase letters
     *  Password Uppercase Letter Error: Password must include uppercase letters
     *  Password Length Short Error: Password must length is shorter then 9 characters.
     * @return OK
     * When the operation was successful.
     */
    public function register(PDO $conn = null){
        if($this->email == null){
            return "NEE";
        }

        if($this->password == null){
            return "NPE";
        }

        if(!Utility::checkEmail($this->email)){
            return "UEE";
        }

        //check if email already exist
        if(Utility::doesEmailExist($this->email)){
            return "EEE";
        }

        //checks the strength of the password
        if(Utility::isPasswordStrong($this->password) !== true){
            return Utility::isPasswordStrong($this->password);
        }

        //There are no errors, so we insert the user into the database and set the ID
        //hash the password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        //Todo
        $tableName = "";
        $column_specs = " email, `password` ";
        $values_spec = "?, ?";
        $values = [$this->email, $this->password];
        $status = Utility::insertIntoTable($tableName, $column_specs, $values_spec, $values, $conn);
        if($status === false){
            return "SQE";
        }
        $this->writerId = $status;
        return "OK";
    }

    /**
     * Login a user 
     * @return EEE|EPE|WPE|WEE|UEE|OK
     * EEE Email Empty error
     * EPE Empty password error
     * WEE Wrong email error
     * WPE Wrong password error  
     * UEE unqualified email error
     */
 
    public function login(&$conn = null)
    {

        $connectionWasPassed = ($conn == null)?false:true;
        if(!$connectionWasPassed){
            $conn = Utility::makeConnection();
        }

        //Check email and passsword not empty
        if(!isset($this->email) || empty($this->email)){
            return "EEE";//email empty error
        }

        if(!Utility::checkEmail($this->email)){
            return "UEE";//unacceptable email error
        }

        if(!isset($this->password) || empty($this->password)){
            return "EPE";//empty password error
        }

        try{
            $tableName = "users";
            $columns = "userId, email, password";
            $values = [$this->email];
            $condition = "email = ?";

            $details = Utility::queryTable($tableName, $columns, $condition, $values, $conn);
 
            
            if($details){
                if(count($details) < 1){

                    //wrong email
                    return "WEE";//wrong email error
                }

                $hashed_password = $details[0]['password'];
                $writerId = $details[0]['userId'];

                if(!password_verify($this->password, $hashed_password)){
                    return "WPE";//wrong password error
                }

                $_SESSION['userId'] = $writerId;
                $this->writerId = $writerId;
                return "OK"; //Quick comment

            }
        }
        catch (Exception $e){

        }
      
    }

    /**
     * Logs out a user and returnt to the home page
     */
    public function logout(){
        if(session_status() == PHP_SESSION_ACTIVE){
            session_destroy();
        }

        header("Location: ../../index.php");
    }

    /**
     * Get the value of writerId
     */ 
    public function getWriterId()
    {
        return $this->writerId;
    }

    /**
     * Set the value of writerId
     *
     * @return  self
     */ 
    public function setWriterId($writerId)
    {
        $this->writerId = $writerId;

        return $this;
    }

    /**
     * Get the value of firstName
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */ 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * This function changes the password of the user.
     * It requires that the old password matches the user current password.
     * If this is not met, then the password is not changed.
     * This method must only be called when you have the Writer id  and the current password is set.
     * @param $oldPassword - The current password of the user
     * @param $newPassword - The new password the user wants to set.
     * @return WCPE|SQLE WCPE: Wrong Current Password Error| SQLE: SQL Error occurred. All the responses
     * for the check password function in the Utility.
     */
    public function changePassword($oldPassword, $newPassword){
        if(!isset($this->writerId)){
            return;
        }

        //make sure the old password matches.
        if(!password_verify($oldPassword, $this->password)){
            return "WCPE"; //Wrong Current Password Error
        }

        //check if the new password meet the requirements
        if(Utility::isPasswordStrong($newPassword) !== true){
            return Utility::isPasswordStrong($newPassword);
        }

        //save to the database
        $tableName = "user";
        $column_specs = "`password` = ?";
        $condition = "userId = ?";
        $values = [password_hash($this->password, PASSWORD_DEFAULT), $this->writerId];
        if(Utility::updateTable($tableName, $column_specs, $condition, $values)){
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            return true;
        }else{
            return "SQLE";//sql error occurred
        }

    }
    /**
     * Get the value of phoneNumber
     */ 
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set the value of phoneNumber
     *
     * @return  self
     */ 
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get the value of nationality
     */ 
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set the value of nationality
     *
     * @return  self
     */ 
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * This saves the state of the Object to the database.
     * It is mostly useful during profile update. This will persist all fields
     * that are not null. The password is changed in `changePassword($oldPassword, $newPassword)`
     *  This method doesn't change the password.
     * @return string UFNE: Unqualified First Name Error | ULNE: Unqualified Last Name Error |
     * UEE: Unqualified Email Error | NEEE: New Email Exist Error | UNE: Unqualified Nationality Error| UPNE: Unqualified Phone Number Error
     * 
     */

    public function persist(PDO $pdo){
        $changeEmail = false;
        $column_specs = "";
        $values = [];
        $reassign_val = [];

      if(isset($this->firstName) && $this->firstName !== null){
            if(!Utility::checkName($this->firstName)){
                return "UFNE";
            }
            $column_specs .= "firstName = ? ";
            $values[] = $this->firstName;
      } 

      if(isset($this->lastName) && $this->lastName !== null){
            if(!Utility::checkName($this->lastName)){
                return "ULNE";
            }
           
            //some columns are before this one.
            if(count($values) > 0){
                $column_specs .", ";
            }
            $column_specs .= "lastName = ? ";
            $values[] = $this->lastName;
      }  

      if(isset($this->email) && $this->email !== null){
            if(!Utility::checkEmail($this->email)){
                return "UEE";
            }
            //check if email is being changed.
            $currentDetails = Utility::queryTable("user", "email", "userId = ?", [$this->writerId]);
            
            if($this->email != $currentDetails[0]['email']){
                //email is being changed.
                //check if the new email already exist in the system
                if(Utility::doesEmailExist($this->email)){
                    return "NEEE";
                }

                //if this is true, we will set the email verification to 0 to make display the confirm email message at the top of the screen when the user logs in.
                $changeEmail = true;
                 //some columns are before this one.
                if(count($values) > 0){
                    $column_specs .", ";
                }
                $column_specs .= "email = ? ";
                $values[] = $this->email;
            }

            if(isset($this->phoneNumber) && $this->phoneNumber !== null){
                if(!Utility::checkPhone($this->phoneNumber)){
                    return "UPNE";
                }
                if(count($values) > 0){
                    $column_specs .", ";
                }
                $column_specs .= "phone = ? ";
                $values[] = $this->phone;
            } 
            
            if(isset($this->nationality) && $this->nationality !== null)
            {
                if(!Utility::checkCountry($this->nationality)){
                    return "UNE";
                }
                if(count($values) > 0){
                    $column_specs .", ";
                }
                $column_specs .= "nationality = ? ";
                $values[] = $this->nationality;
            }
            
            //everything is okay
            //update the database
            //If the email is to be changed, then save it in the temporary table until it is verified.
            //Todo
            ///-----------------------
            //update the database
            if(Utility::updateTable('user', $column_specs, "userId = ?", $values, $pdo)){
                return true;
            }
            else{
                return false; //Quick check
            }

            //reconstruct this object
      }  
    }
 }

?>