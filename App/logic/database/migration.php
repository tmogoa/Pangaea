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

                $sql = "CREATE TABLE users 
                (
                	userId INT(20) UNSIGNED PRIMARY KEY,
                	firstname VARCHAR(20) NOT NULL,
                	lastname VARCHAR(20) NOT NULL,
                	phone CHAR(13) NOT NULL,
                	email CHAR(50) NOT NULL,
                	password VARCHAR (50) NOT NULL,
                	preferredArticleTopics VARCHAR(50),
                	isSubscribed INT(100)


                )";

                    $conn->prepare($sql);

                $sql = "CREATE TABLE ArticleTopics
                (

                aTopicId INT(20) UNSIGNED PRIMARY KEY,
                topic VARCHAR (30),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                )";

                $sql = "CREATE TABLE Article
                (
                	articleId INT(20) UNSIGNED PRIMARY KEY,
                	writerId INT(20) UNSIGNED,
                	articleText TEXT,
                	publishStatus enum('published', 'draft'),
                	shares INT, 
                	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                	published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

                	FOREIGN KEY (writerId) REFERENCES users(userId)

                )";

                $sql = "CREATE TABLE ArticleReaction 
                (
                	aReactionId INT(20) UNSIGNED PRIMARY KEY,
                	articleId INT (20) UNSIGNED,
                	applaudedBy INT(20) UNSIGNED,
                	FOREIGN KEY (applaudedBy) REFERENCES users(userId),
                	FOREIGN KEY (articleId) REFERENCES Article(articleId)

                )";

                $sql = "CREATE TABLE Reading
                (
                	readingId INT(20) UNSIGNED PRIMARY KEY,
                	readerId INT(20) UNSIGNED,
                	FOREIGN KEY (readerId) REFERENCES users(userId),
                	articleId INT(20) UNSIGNED,
                	timeReading 
                	FOREIGN KEY (articleId) REFERENCES Article(articleId)


                )" 





                $conn->commit();
            }
            catch(Exception $e){
                $conn->rollBack();
            }

        }
    }

?>