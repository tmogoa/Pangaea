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
            private $featuredImage;
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
             * The article is saved as a draft.
             * The writer ID must be set for the article class. It is a static property.
             */
            public function addArticle($writerId, &$conn = null){
                
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
        
                $has_tags = false;
                if(isset($this->tags) && is_array($this->tags)){
                    $has_tags = true;
                }

                //initializing the system set variables
                $this->publishStatus = "draft";
                
                /*
                    $sql = "CREATE TABLE Article
                (
                	articleId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                	writerId INT(20) UNSIGNED,
                    title VARCHAR(500) NOT NULL,
                    subtitle VARCHAR(500),
                	body TEXT NOT NULL,
                	publishStatus enum('published', 'draft'),
                	shares INT DEFAULT 0, 
                	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                	published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                	FOREIGN KEY (writerId) REFERENCES users(userId)

                )";
                */
                
                //firstly dealing with the Article table

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