<?php

/**
 * This class keeps track of a reader's record and holds the payment methods
 */
 class Reader extends Writer{

    private $recommendedArticles = [];
    private $preferredArticleTopics = [];
    /**
     * The reader Subscription is represented as integer
     * 0 - free user, 1 - paid (false or true)
     */
    private $isSubscribed;

    /**
     * If the writer Id is passed (the reader Id),
     * then we will fetch this reader information from the database.
     * By default, it is false.
     * 
     * The recommended articles array field is not set by default.
     * therefore, when ever it is called (the get recommended articles is computed)
     * 
     * If you already have a connection object on your script, you can go ahead to pass the connection
     */
    public function __construct($writerId = false, $conn = null)
    {
        parent::__construct($writerId, $conn);
        if($writerId !== false){
            $connectionWasPassed = ($conn == null)?false:true;
            if(!$connectionWasPassed){
                $conn = Utility::makeConnection();
            }
            //todo
            $tableName = "users";
            $column_specs = "preferredArticleTopics, isSubscribed";
            $condition = "userId = ?";
            $values = [$writerId];
            $details =  Utility::queryTable($tableName, $column_specs, $condition, $values, $conn);
            $this->isSubscribed = ($details[0]['isSubscribed'] == 0)?false:true;
            /**
             * The preferredArticlesTopics is stored as a JSON array in the database
             */
            $this->preferredArticleTopics = json_decode($details[0]['preferredArticleTopics']);

            if(!$connectionWasPassed){
                $conn = null;
            }
        }
        
    }

    /**
     * When a user applauds an article, its applauses increases and we will use it to recommend articles to the reader later.
     * The writerId
     */
    public function applaudArticle($articleId, &$conn = false){
        $connectionWasPassed = ($conn == null)?false:true;
        if(!$connectionWasPassed){
            $conn = Utility::makeConnection();
        }

        $article = new Article();
        $article->setId($articleId);
        return $article->applaud($this->writerId);
    }

    /**
     * 
     */
    public function getReadTimePerArticle($articleId){

    }

    public function paySubscriptionFee($articleId){

    }

    public function reportArticle($articleId, $commplaint){

    }
    

 }

?>