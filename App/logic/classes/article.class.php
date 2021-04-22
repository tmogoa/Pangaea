<?php

use GuzzleHttp\Promise\Utils;

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
            private $readTime;
            private $numberOfReaders;
            private $numberOfComments;
            private $comments = [];//will be fetched from the database when called. we don't want a heavy object. 
            /**
             * Creates an article with no field set.
             * @param int $id - pass the id if you want the article to be constructed from the database.
             * @param PDO $conn - pass the connection if you already have a connection to use. 
             */
            public function __construct($id = false, &$conn = null){
                if($id != false){
                    $connectionWasPassed = ($conn == null)?false:true;
                    if(!$connectionWasPassed){
                            $conn = Utility::makeConnection();
                    }
                    $tableName = "article";
                    $column_specs = "*";
                    $condition = "articleId = ?";
                    $values = [$id];
                    $result = Utility::queryTable($tableName, $column_specs, $condition, $values, $conn);

                    if($result){

                        $result = $result[0];
                        $this->id = $id;
                        $this->title = $result['title'];
                        $this->subtitle = $result['subtitle'];
                        $this->body = $result['body'];
                        $this->writerId = $result['writerId'];
                        $this->publishStatus = $result['publishStatus'];
                        $this->featuredImage = $result['featured_image'];
                        $this->dateUpdated = $result['updated_at'];
                        $this->dateCreated = $result['created_at'];
                        $this->datePublished = $result['published_at'];
                        $this->shares = $result['shares'];
                        
                        //getting applauds
                        $applauds = Utility::queryTable("articleReaction", "Count(aReactionId) as applauds", "articleId = ?", [$this->id], $conn);
                        if($applauds){
                            if(count($applauds) > 0){
                                $this->applauds = $applauds[0]['applauds'];
                            }
                            else{
                                $this->applauds = 0;
                            }
                        }

                        //readTime
                        $this->readTime = round(count(preg_split("/\s+/", $this->body, 0))/200);
                       
                        //calculating the number of readers
                        $numberOfReaders = Utility::queryTable("reading", "count(readingId) as numberOfReaders", "articleId = ?", [$this->id], $conn);

                        if($numberOfReaders && count($numberOfReaders) > 0){
                            $this->numberOfReaders = $numberOfReaders[0]['numberOfReaders'];
                        }else{
                            $this->numberOfReaders = 0;
                        }

                        //calculating the number of comments
                        $numberOfComments = Utility::queryTable("comment", "count(commentId) as numberOfComments", "articleId = ?", [$this->id], $conn);

                        if($numberOfComments && count($numberOfComments) > 0){
                            $this->numberOfComments = $numberOfComments[0]['numberOfComments'];
                        }else{
                            $this->numberOfComments = 0;
                        }

                        //setting the tags
                        $tags = Utility::queryTable("articleTags", "tagId", "articleId = ?", [$this->id], $conn);

                        if($tags && count($tags) > 0){
                            foreach($tags as $tag){
                                array_push($this->tags, $tag['tagId']);
                            }
                        }

                        //tags set

                    }else{
                        throw new Exception("Could not construct the object of type Article");
                    }

                    if(!$connectionWasPassed){
                        $conn = null;
                    }
                }


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
                    $column_specs = "publishStatus = ?, published_at = CURRENT_TIMESTAMP";
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

            /**
             * This function applauds an article or remove the applaud.
             */
            public function applaud($readerId){

                if(Utility::queryTable("articleReaction", "aReactionId", "articleId = ? and readerId = ?", [$this->id, $readerId])){
                   return $this->decreaseApplauds($readerId);
                }else{
                   return $this->increaseApplauds($readerId);
                }
                return false;
            }

            /**
             * Updates changes made to an article. 
             * This function requires that the article id be set.
             * Only the ones that should be updated should be set. All others should remain null.
             * Only title, tags, subtitle, body, and featured image can be updated
             * the setters will sanitize the inputs
             */
            public function persist(&$conn = null){
                $connectionWasPassed = ($conn == null)?false:true;
                if(!$connectionWasPassed){
                    $conn = Utility::makeConnection();
                }

                if(!isset($this->id)){ // found a bug here changed it from $this->articleId
                    return "NIE";//Null ID Error;
                }

                $column_specs = "";
                $values = [];

                if(isset($this->title) && !empty($this->title)){
                    $column_specs = "title = ?";
                    $values[] = $this->title;
                }

                if(isset($this->subtitle) && !empty($this->subtitle)){
                    if(count($values) > 0){
                        $column_specs .=", ";
                    }
                    $column_specs = "subtitle = ?";
                    $values[] = $this->subtitle;
                }

                if(isset($this->body) && !empty($this->body)){
                    if(count($values) > 0){
                        $column_specs .=", ";
                    }
                    $column_specs = "body = ?";
                    $values[] = $this->body;
                }

                //update the tags first (No return value)
                //update the tags table
                $numOfTags =  count($this->tags);
                if($numOfTags > 0){
                    $t_values = [];
                    $_t = "articleTags";
                    $t_columns_spec = "articleId, tagId";
                    $t_values_specs = "";

                    $_c = "articleId = ?";
                    $_v[] = $this->id;
                
                    //clearing the database
                    if(Utility::deleteFromTable($_t, $_c, $_v, $conn)){

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
                        Utility::insertIntoTable($_t, $t_columns_spec, $t_values_specs, $t_values,$conn);
                    }
                }

                //updating the feature image link
                if(isset($this->featuredImage) && !empty($this->featuredImage)){
                    if(count($values) > 0){
                        $column_specs .=", ";
                    }
                    $column_specs = "featured_image = ?";
                    $values[] = $this->featuredImage;
                }

                //update the actual table
                $tableName = "article";
                $condition = "articleId = ?";
                if(count($values) > 0){
                    $column_specs .= ", ";
                }
                $column_specs .= "updated_at = CURRENT_TIMESTAMP";
                $values[] = $this->id;

                if(Utility::updateTable($tableName, $column_specs, $condition, $values, $conn)){
                    return "OK";
                }
                else{
                    return "SQE";//sql error
                }
                
                

                //update the article
                $tableName = "article";
                $condition = "articleId = ?";
                $values[] = $this->id;

                if(Utility::updateTable($tableName, $column_specs, $condition, $values, $conn)){

                }

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

                //adding the writer id
                if(count($values) > 0){
                    $column_specs .= ", ";
                }

                $column_specs .= "writerId";
                $values[] = $writerId;


                $tableName = "article";
                $this->id = Utility::insertIntoTable($tableName, $column_specs, $values_specs, $values, $conn);
        
                //update the tags table
                $numOfTags =  count($this->tags);
                if($numOfTags > 0){
                    $t_values = [];
                    $tableName = "articleTags";
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

                $body = htmlspecialchars_decode($this->body);
                $body = json_decode($body);

                $str_body = "";
                foreach($body->blocks as $block){
                    $str_body .= $block->data;
                }


                $keywords = $this->title + " "+$this->subtitle+" "+$str_body;

                //dealing with tags
                if(count($this->tags) > 0){
                
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
             * Getters and setters
             * --------------------------------------------------------------------
             */
            

            /**
             * Get the value of id
             */ 
            public function getId()
            {
                        return $this->id;
            }

            /**
             * Set the value of id
             *
             * @return  self
             */ 
            public function setId($id)
            {
                        $this->id = $id;

                        return $this;
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
             * Get the value of body
             */ 
            public function getBody()
            {
                        return $this->body;
            }

            /**
             * Set the value of body
             *
             * @return  self
             */ 
            public function setBody($body)
            {
                //sanitize the body
                        $body = Utility::sanitizeTextEditorInput($body);
                        $this->body = $body;

                        return $this;
            }

            /**
             * Get the value of title
             */ 
            public function getTitle()
            {
                        return $this->title;
            }

            /**
             * Set the value of title
             *
             * @return  self
             */ 
            public function setTitle($title)
            {
                        $title = Utility::sanitizeTextEditorInput($title);
                        $this->title = $title;

                        return $this;
            }

            /**
             * Get the value of subtitle
             */ 
            public function getSubtitle()
            {
                        return $this->subtitle;
            }

            /**
             * Set the value of subtitle
             *
             * @return  self
             */ 
            public function setSubtitle($subtitle)
            {
                        $subtitle = Utility::sanitizeTextEditorInput($subtitle);
                        $this->subtitle = $subtitle;

                        return $this;
            }

            /**
             * Get the value of tags
             */ 
            public function getTags()
            {
                        return $this->tags;
            }

            /**
             * Set the value of tags
             * @return false on failure
             * @return  self
             */ 
            public function setTags(array $tags)
            {
                        if(!is_array($tags)){
                            return false;
                        }

                        $this->tags = $tags;

                        return $this;
            }

            /**
             * Get the value of publishStatus
             */ 
            public function getPublishStatus()
            {
                        return $this->publishStatus;
            }

            /**
             * Set the value of publishStatus
             *
             * @return  self
             */ 
            public function setPublishStatus($publishStatus)
            {
                        $publishStatus = strtolower($publishStatus);
                        if($publishStatus != "published"){
                            $publishStatus = "draft";
                        }
                        $this->publishStatus = $publishStatus;
                        return $this;
            }

            /**
             * Get the value of featuredImage
             */ 
            public function getFeaturedImage()
            {
                        return $this->featuredImage;
            }

            /**
             * Set the value of featuredImage
             * @param $featuredImage - The name of the image, not the path
             * This takes the Id of the featureImage and converts the image name to a unique name
             * for the article. This is not a critical operation and hence is void.
             * Feature images are titled feat-img-articleId-uniqueid 
             * @return  self
             */ 
            public function setFeaturedImage($featuredImageLink)
            {
                $this->featuredImage = $featuredImageLink;
                //The id of the article must be set
                return $this;
            }

            /**
             * Get the value of dateCreated
             */ 
            public function getDateCreated()
            {
                        return $this->dateCreated;
            }

            /**
             * Get the value of dateUpdated
             */ 
            public function getDateUpdated()
            {
                        return $this->dateUpdated;
            }

            /**
             * Get the value of datePublished
             */ 
            public function getDatePublished()
            {
                        return $this->datePublished;
            }

            /**
             * Set the value of datePublished
             *
             * @return  self
             */ 
            public function setDatePublished($datePublished)
            {
                        $this->datePublished = $datePublished;

                        return $this;
            }

            /**
             * Get the value of applauds
             */ 
            public function getApplauds()
            {
                        return $this->applauds;
            }

            /**
             * Set the value of applauds
             * Decrease the applauds by 1. The reader Id must be passed
             * @return  self
             */ 
            public function decreaseApplauds($readerId, &$conn = null)
            {
                        $connectionWasPassed = ($conn != null)?true:false;
                        if(!$connectionWasPassed){
                            $conn = Utility::makeConnection();
                        }

                        if(Utility::deleteFromTable("articleReaction", "applaudedBy = ? and articleId = ?", [$readerId, $this->id], $conn)){
                            $this->applauds = $this->applauds - 1;
                        }else{
                            return false;
                        }
                       
                        if(!$connectionWasPassed){
                            $conn = null;
                        }

                        return true;
            }

             /**
             * Set the value of applauds
             * Decrease the applauds by 1. The reader Id must be passed
             * @return  self
             */ 
            public function increaseApplauds($readerId, &$conn = null)
            {
                        $connectionWasPassed = ($conn != null)?true:false;
                        if(!$connectionWasPassed){
                            $conn = Utility::makeConnection();
                        }

                        if(Utility::insertIntoTable("articleReaction", "articleId, applaudedBy", "?, ?", [$this->id, $readerId], $conn)){
                            $this->applauds = $this->applauds + 1;
                        }else{
                            return false;
                        }
                       
                        if(!$connectionWasPassed){
                            $conn = null;
                        }

                        return true;
            }

            /**
             * Get the value of shares
             */ 
            public function getShares()
            {
                        return $this->shares;
            }

            /**
             * Set the value of shares
             *
             * @return  self
             */ 
            public function setShares($shares)
            {
                        $this->shares = $shares;

                        return $this;
            }

            /**
             * Get the value of readTime
             */ 
            public function getReadTime()
            {
                        return $this->readTime;
            }

            /**
             * Set the value of readTime
             *
             * @return  self
             */ 
            public function setReadTime()
            {
                        $this->readTime = round(count(preg_split("/\s+/",$this->body, 0))/200);
                        return $this;
            }

            /**
             * Get the value of numberOfReaders
             */ 
            public function getNumberOfReaders()
            {
                        return $this->numberOfReaders;
            }

            /**
             * Set the value of numberOfReaders
             * This function is only called when the threshold for reading is met.
             * It is only called by the reader object. 
             * There can be duplicate rows in the reading table. A reader can read an article and 
             * reads it again.
             * @return  self
             */ 
            public function increaseNumberOfReaders($readerId, &$conn = null)
            {
                        if(Utility::insertIntoTable("reading", "readerId, articleId", "?, ?", [$readerId, $this], $conn)){
                            $this->numberOfReaders = $this->numberOfReaders + 1;
                        }
                        
                        return $this;
            }

            /**
             * Get the value of numberOfComments
             */ 
            public function getNumberOfComments()
            {
                        return $this->numberOfComments;
            }

            /**
             * Set the value of numberOfComments
             *
             * @return  self
             */ 
            public function setNumberOfComments($numberOfComments)
            {
                        $this->numberOfComments = $numberOfComments;

                        return $this;
            }

            /**
             * Get the value of comments
             * This fetches the comments from the database and returns them.
             */ 
            public function getComments()
            {
                        if(!isset($this->id)){
                            return "NIE";//Null Id error
                        }

                        $tableName = "comment";
                        $column_specs = "*";
                        $condition = "articleId = ? order by created_at";
                        $values = [$this->id];

                        $comments = Utility::queryTable($tableName, $column_specs, $condition, $values);

                        if($comments){
                            return $comments;
                        }
                        
                        return $this->comments;
            }

            /**
             * Set the value of comments
             *
             * @return  self
             */ 
            public function setComments($comments)
            {
                        $this->comments = $comments;

                        return $this;
            }
    }

?>