<?php

/**
 * This is the article class.
 * It contains methods that allows the creation and uploading of articles.
 * When a new article is created, we call the constructor of this class with no parameter passed to create an empty Article. 
 * The article properties will be set then the addArticle method will be called which adds the article to the system.
 */
    class Article{
            private $articleId;
            private $articeText;
            private $articleTitle;
            private $articleSubtitle;
            private $articleTags = [];
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
             * @param int $articleId - pass the id if you want the article to be constructed from the database.
             * @param PDO $conn - pass the connection if you already have a connection to use. 
             */
            public function __construct($articleId = false, $conn = null){
                
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
             * The article object must have the required fields set. These fields are 
             */
            public function addArticle(){
                
            }

            /**
             * -------------------------------------------------------------------
             * These classes are static classes and belong solely to the article management
             * functionality
             * --------------------------------------------------------------------
             */

    }

?>