<?php

/**
 * This is the article class.
 * It contains methods that allows the creation and uploading of articles.
 * When a new article is created, we call the constructor of this class with no parameter passed to create an empty Article. 
 * The article properties will be set then the addArticle method will be called which adds the article to the system.
 */
    class Article{
            private $id;
            private $writerId;
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

            /**
             * Sets or gets the publish status of the article. 
             * If the article is published, pass true into this function else, pass false.
             * If you call the function without any parameter, it returns the publish status of
             * the article.
             */
            public function isPublished($status = null){
                if($status != null){
                    //set the status and return the new status
                    $tableName = "article";
                    $column_specs = "publishStatus = ?";
                    $condition = "articleId = ?";
                    if($status == true){
                        $isPublished = "published";
                    }
                    else{
                        $isPublished = "draft";
                    }
                    $values = [$isPublished, $this->id];

                    if(Utility::updateTable($tableName, $column_specs, $condition, $values)){
                        $this->publishStatus = $isPublished;
                    }
                }
                //get the status
                return ($this->publishStatus == "published")?true:false;
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
                        $values_specs .= "?";
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
                $values_specs .= "?";
                $column_specs .= "body";
        
                //Tags are IDs sent along with the data in the form of a json format

                //initializing the system set variables
                $this->publishStatus = "draft";
                if(count($values) > 0){
                    $column_specs .= ", ";
                    $values_specs .= ", ";
                }
                $values_specs .= ", ";
                $column_specs .= "publishStatus";
             

                //firstly dealing with the Article table

                $connectionWasPassed = ($conn == null)?false:true;
                if(!$connectionWasPassed){
                        $conn = Utility::makeConnection();
                }

                $tableName = "article";
                $this->id = Utility::insertIntoTable($tableName, $column_specs, $values_specs, $values, $conn);
        
                //update the tags table
                $numOfTags =  count($this->tags);
                if($numOfTags > 0){
                    $t_values = [];
                    $t_columns_spec = "articleId, tagId";
                    $t_values_specs = "";
                    
                    for($i = 0; $i < $numOfTags; $i++){
                        if($i == 0){
                            $t_values_specs = "?, ?";
                        }
                        else {
                            $t_values_specs .= "), (?, ?";
                        }
                        array_push($t_values, $this->id, $this->tags[$i]);
                    }

                    //insert the rows
                    //Error might occur here
                    Utility::insertIntoTable($tableName, $t_columns_spec, $t_values_specs, $t_values,$conn);

                }


                if(!$connectionWasPassed){
                    $conn = null;
                }

                return "OK";
            }


            /**
             * Aid in the publishing of articles
             * This function requires that the article be constructed from the database and change it's status to publishing.
             * Additionally, It generates the keywords of the Article and queues it for indexing.
             */

            public function publish(&$conn = null){
                if(!isset($this->id)){
                    return "NIE"; //Null ID error
                }

                //constructing the keywords
                //The keywords is made form the Author's name, title, subtitle, body, and tag names of the article. 
                $connectionWasPassed = ($conn == null)?false:true;
                if(!$connectionWasPassed){
                        $conn = Utility::makeConnection();
                }

                $keywords = $this->title + " "+$this->subtitle+" "+$this->body;

                //dealing with tags
                $tags = json_encode($this->tags);
                //tags are now in [1, 2, 3] therefore, can be used in a query
        
                $tags = substr($tags, 1, strlen($tags) - 2); //getting raid of the [] brackets
                //getting the PDO accepted format
                $tags = preg_replace("/^(\d+)/", "?", $tags); //now in ?,?, ?, format
                $tags = "($tags)";
                $tableName = "articleTopics";
                $column_specs = "topic";
                $condition = "aTopicId in $tags";
                
                $tagNames = Utility::queryTable($tableName, $column_specs, $condition, $this->tags);

                //appending tag names to the keywords
                foreach($tagNames as $tagName){
                    $keywords .= " $tagName ";
                }

                //get the author's name and email
                $tableName = "users";
                $column_specs = "firstname, lastname, email ";
                $condition = "userId = ?";
                $values = [$this->writerId];
                $authorCre = Utility::queryTable($tableName, $column_specs, $condition, $values, $conn);

                foreach($authorCre as $authorCred){
                    if(!empty($authorCred['firstname'])){
                        $keywords .= " ". $authorCred['firstname'];
                    }

                    if(!empty($authorCred['lastname'])){
                        $keywords .= " ". $authorCred['lastname'];
                    }

                    $keywords .= " ". $authorCred['email'];
                }
                //the keywords is are now ready to be stemmed

                $keywords = preg_split("/\s+/", $keywords, 0);
                $keywords = Utility::removeStopwords($keywords);
                $keywords = array_map("PorterStemmer::Stem", $keywords);

                //implode the array
                $keywords = " ".implode(" ", $keywords). " ";//do not touch the space around this 
                
                $tableName = "articleKeywords";
                $column_specs = "articleId, keywords, is_indexed";
                $values_specs = "?, ?, ?";
                $values = [$this->id, $keywords, 0];

                $keywordsId = Utility::insertIntoTable($tableName, $column_specs, $values_specs, $values, $conn);

                if(is_int($keywordsId)){
                    $this->isPublished(true);
                    return true;
                }

                return false;
            }
            /**
             * -------------------------------------------------------------------
             * These classes are static classes and belong solely to the article management
             * functionality
             * --------------------------------------------------------------------
             */

    }

?>