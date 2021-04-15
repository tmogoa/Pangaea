<?php

/**
 * This is the article class.
 * It contains methods that allows the creation and uploading of articles.
 * When a new article is created, we call the constructor of this class with no parameter passed to create an empty Article. 
 * The article properties will be set then the addArticle method will be called which adds the article to the system.
 */
    class Article{
            private $id;
            private $body;
            private $title;
            private $subtitle;
            private $tags = [];
            private $publishStatus;
            private $media = [];
            private $dateCreated;
            private $dateUpdated;
            private $datePublished;
            private $applauds;
            private $shares;
            private $reaTime;
            private $numberOfReaders;

            /**
             * Creates an article with no field set.
             * @param int $id - pass the id if you want the article to be constructed from the database.
             * @param PDO $conn - pass the connection if you already have a connection to use. 
             */
            public function __construct($id = false, $conn = null){
                
            }

            public function applaud($readerId){
                return false;
            }

            public function persist(){

            }

            public function calculateRating($readerId = 0){

            }

            /**
             * This function allows the addition of an article.
             * The article object must have the required field, article title set.
             * The article is saved as a draft
             */
            public function addArticle(&$conn = null){
                
                $column_specs = "title";
                $values_specs = "?";
                $values = [];
        
                if(!isset($this->title) || empty($this->title)){
                    return "ETE";//empty title error
                }

                //Will be looked at if this were to go live
                $this->title = Utility::sanitizeTextEditorInput($this->title);
                //article title
                $values = [$this->title];

                if(isset($this->subtitle) && !empty($this->subtitle)){
                        $this->subtitle = Utility::sanitizeTextEditorInput($this->subtitle);

                        if(count($values) > 0){
                            $column_specs .= ", ";
                            $values_specs .= ", ";
                        }
                        $values_specs .= ", ";
                        $column_specs .= "subtitle";
                        
                        $values[] = $this->subtitle;
                } 
            
                //add the article text
                if(!isset($this->body) || empty($this->body)){
                         return "EBE";//empty body error
                }  

                //add the article body
                $this->body = Utility::sanitizeTextEditorInput($this->body);

                if(count($values) > 0){
                    $column_specs .= ", ";
                    $values_specs .= ", ";
                }
                $values_specs .= ", ";
                $column_specs .= "body";
        
              if(isset($this->email) && $this->email !== null){
                    if(!Utility::checkEmail($this->email)){
                        return "UEE";
                    }
                    //check if email is being changed.
                    $currentDetails = Utility::queryTable("users", "email", "userId = ?", [$this->writerId]);
                    
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
                $connectionWasPassed = ($conn == null)?false:true;
                if(!$connectionWasPassed){
                        $conn = Utility::makeConnection();
                }

                if(Utility::updateTable('users', $column_specs, "userId = ?", $values, $conn)){
                    return true;
                }
                else{
                    return false; //Quick check
                }
        
            }

            /**
             * -------------------------------------------------------------------
             * These classes are static classes and belong solely to the article management
             * functionality
             * --------------------------------------------------------------------
             */

    }

?>