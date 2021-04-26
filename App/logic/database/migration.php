<?php
    //auto load classes
    spl_autoload_register(function($name){
        require_once("./App/logic/classes/$name.class.php");
        
    });

    class DatabaseCreator{

        /**
         * Write the transaction that will create the tables in this function
         */
        public static function clearDatabase(){
            $conn = utility::makeConnection();
            $conn->query("drop database IF EXISTS pangaea_db");
            $conn->query("create database pangaea_db");
        }

        public static function makeDatabase(){
            /**
             * Please make sure to set your database name in the .env file.
             */
            self::clearDatabase();

            $conn = utility::makeConnection();

            try{
                $conn->beginTransaction();

                //Abart, please write the db creation statement in here. Make sure to add drop if exist or something to check if the table already exist if you only want to update it.

                //We reomoved the not null from on some columns
                $sql = "CREATE TABLE users 
                (
                	userId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                	firstname VARCHAR(20) ,
                	lastname VARCHAR(20) ,
                	phone VARCHAR(15) ,
                	email VARCHAR(255) NOT NULL,
                	`password` VARCHAR (256) NOT NULL,
                	preferredArticleTopics VARCHAR(255),
                	isSubscribed TINYINT default 0,
			        profile_image VARCHAR(500)
                )";

                $stmt1 =  $conn->prepare($sql);
                $stmt1->execute();

                $sql = "CREATE TABLE articleTopics
                (

                aTopicId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                topic VARCHAR (255),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
                
                $stmt2 =  $conn->prepare($sql);
                $stmt2->execute();

                //I already made some updates
                $sql = "CREATE TABLE article
                (
                	articleId INT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                	writerId INT(20) UNSIGNED NOT NULL,
                    title VARCHAR(500),
                    subtitle VARCHAR(500),
                	body TEXT,
                	publishStatus enum('published', 'draft'),
                	shares INT DEFAULT 0,
                    featured_image VARCHAR(1000), 
                	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                	published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                	FOREIGN KEY (writerId) REFERENCES users(userId)

                )";

                $stmt3 =  $conn->prepare($sql);
                $stmt3->execute();

                $sql = "CREATE TABLE articleReaction 
                (
                	aReactionId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                	articleId INT (20) UNSIGNED,
                	applaudedBy INT(20) UNSIGNED,
                	FOREIGN KEY (applaudedBy) REFERENCES users(userId),
                	FOREIGN KEY (articleId) REFERENCES article(articleId)

                )";

                $stmt4 =  $conn->prepare($sql);
                $stmt4->execute();

                $sql = "CREATE TABLE reading
                (
                	readingId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                	readerId INT(20) UNSIGNED,
                	FOREIGN KEY (readerId) REFERENCES users(userId),
                	articleId INT(20) UNSIGNED,
                    timeReading INT,
                    read_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
                	FOREIGN KEY (articleId) REFERENCES article(articleId)


                )";
                
                $stmt5 =  $conn->prepare($sql);
                $stmt5->execute();

                $sql = "CREATE TABLE articleTags 
                (
                tagRefId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                articleId INT(20) UNSIGNED,
                tagId INT(20) UNSIGNED,

                FOREIGN KEY (tagId) REFERENCES articleTopics(aTopicId),
                FOREIGN KEY (articleId) REFERENCES article(articleId)
		        )";
		
                $stmt6 =  $conn->prepare($sql);
                $stmt6->execute();

                $sql = "CREATE TABLE articleKeywords
                (
                        keywordId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                        articleId INT(20) UNSIGNED,
                        keywords TEXT,
                        is_indexed INT DEFAULT 0, 
                        FOREIGN KEY (articleId) REFERENCES article(articleId)
                )";
          
                $stmt7 =  $conn->prepare($sql);
                $stmt7->execute();
		    
                $sql = "CREATE TABLE `index`
                (
                    termId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                    term VARCHAR(255),
                    docfreq INT UNSIGNED
                )";
          
                $stmt8 =  $conn->prepare($sql);
                $stmt8->execute();

                $sql = "CREATE TABLE `comment`
                (
                    commentId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                    readerId INT unsigned not null,
                    articleId INT unsigned not null,
                    `comment` text not null,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    foreign key(readerId) references users(userId),
                    foreign Key(articleId) references article(articleId)
                )";
          
                $stmt9 =  $conn->prepare($sql);
                $stmt9->execute();
		    
		        $sql = "CREATE TABLE subscriptionPayment
                (
                subPaymentId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                readerId INT(20) UNSIGNED,
                merchantId VARCHAR(500),
                checkoutRequestId VARCHAR(500),
                payer VARCHAR(20),
                transactionId VARCHAR(255) DEFAULT NULL,
                transactionDate DATETIME DEFAULT NULL,
                resultCode INT DEFAULT -1,
                month INT,
                year INT,
                FOREIGN KEY (readerId) REFERENCES users(userId)
                )";
		
                $stmt10 = $conn->prepare($sql);
                $stmt10 ->execute();
                
                $sql = "CREATE TABLE earning
                (
                earningId INT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                readerId INT(20) UNSIGNED,
                articleId INT(20) UNSIGNED,
                amount INT,
                `month` INT,
                `year` INT,
                earned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                
                FOREIGN KEY(articleId) references article(articleId),		
                FOREIGN KEY (readerId) REFERENCES users(userId)
                )";
                    
                $stmt11 = $conn->prepare($sql);
                $stmt11 ->execute();


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
