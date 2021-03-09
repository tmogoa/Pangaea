<?php

/**
 * Writer class. This class is the super class for the Reader class.
 */
 class Writer{
    private $writerId, $firstName, $lastName, $email, $password, $phoneNumber, $nationality;
    /**
     * all the articles that a writer has written.
     */
    private $aritcles = [];

    /**
     * Creates a writer object with no field set.
     * Every field must be set, including the Id.
     * If you want to create a writer from the database, call
     * Writer(writerId: int);
     */
    public function __construct(){

    }

    /**
     * Registers a new user.
     * Before this function is called, these fields must be set: email, password. All other fields can be
     * updated using the edit profile module.
     */
    public function register(PDO $conn = null){

    }

    public function login(PDO $conn = null){

    }

    public function logout(PDO $conn = null){

    }

    public function __call($name, $arguments)
    {
        switch($name){
            //a constructor with an WriterId parameter was called
            case "Writer":
                {
                    $writer = new Writer();
                    $writer->setWriterId($arguments[0]);
                    //set the writer details
                    break;
                }
        }
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
 }

?>