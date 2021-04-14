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
                	userId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                	firstname VARCHAR(20) NOT NULL,
                	lastname VARCHAR(20) NOT NULL,
                	phone VARCHAR(15) NOT NULL,
                	email VARCHAR(255) NOT NULL,
                	password VARCHAR (256) NOT NULL,
                	preferredArticleTopics VARCHAR(255),
                	isSubscribed TINYINT default 0
                )";

                $stmt1 =  $conn->prepare($sql);
                $stmt1->execute();

                $sql = "CREATE TABLE ArticleTopics
                (

                aTopicId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                topic VARCHAR (255),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
                
                $stmt2 =  $conn->prepare($sql);
                $stmt2->execute();

                $sql = "CREATE TABLE Article
                (
                	articleId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                	writerId INT(20) UNSIGNED,
                	articleText TEXT,
                	publishStatus enum('published', 'draft'),
                	shares INT DEFAULT 0, 
                	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                	published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

                	FOREIGN KEY (writerId) REFERENCES users(userId)

                )";

                $stmt3 =  $conn->prepare($sql);
                $stmt3->execute();

                $sql = "CREATE TABLE ArticleReaction 
                (
                	aReactionId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                	articleId INT (20) UNSIGNED,
                	applaudedBy INT(20) UNSIGNED,
                	FOREIGN KEY (applaudedBy) REFERENCES users(userId),
                	FOREIGN KEY (articleId) REFERENCES Article(articleId)

                )";

                $stmt4 =  $conn->prepare($sql);
                $stmt4->execute();

                $sql = "CREATE TABLE Reading
                (
                	readingId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                	readerId INT(20) UNSIGNED,
                	FOREIGN KEY (readerId) REFERENCES users(userId),
                	articleId INT(20) UNSIGNED, timeReading INT,  
                	FOREIGN KEY (articleId) REFERENCES Article(articleId)


                )";
                
                $stmt5 =  $conn->prepare($sql);
                $stmt5->execute();


                $conn->commit();
            }
            catch(Exception $e){
                echo $e->getMessage();
                $conn->rollBack();
            }

        }
    }

    DatabaseCreator::makeDatabase();

?>