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
     * Get the number of reads this reader has made to calculate his subscription fee.
     */
    public function getReadTimePerArticle($articleId){

    }

    /**
     * Read article. 
     * This function sets the time a reader spends reading an article
     */
    public function read($articleId, $seconds){
        if(Utility::insertIntoTable("reading", "readerId, articleId, timeReading", "?,?", [$this->writerId, $articleId, $seconds])){
            echo "OK";
        }else{
            echo "UE";
        }
    }

    /**
     * The user pays the required monthly subscription fee through STK push.
     * The user's Mpesa details must be set.
     */
    public function paySubscriptionFee(){
        if(!isset($this->phoneNumber) || empty($this->phoneNumber)){
            return "NPNE";//No Phone Number Error
        }

        $amount = 100; //100ksh per month
        //check the phone number
        if(strlen($this->phoneNumber) == 10){
            //check if there is a 0
            if($this->phoneNumber[0] == "0"){
                $this->phoneNumber = "254".substr($this->phoneNumber, 1); //converting 0740958965 to 254740958965
            }
        }

        //if we have 9 digits which I know we wont. Anyway, lemme be sure
        if(strlen($this->phoneNumber) == "9"){
            $this->phoneNumber = "254".$this->phoneNumber;
        }

        if(strlen($this->phoneNumber) == 13){
            //check if there is a +
            if($this->phoneNumber[0] == "+"){
                $this->phoneNumber = substr($this->phoneNumber, 1);
            }
        }

        //When the user pays, they will confirm with a button
        return Mpesa::stkPush($this->phoneNumber, $amount);
    }

    /**
     * This function verifies that the reader has paid the month's subscription fees
     */
    public function hasPaid(){
        //we will check for 10 seconds if the user has paid
        $month = date("F");
        $year = date("Y");

        $sql = "SELECT resultCode from subscriptionPayment where readerId = ? and `month` = ? and  `year` = ?";
        $conn = Utility::makeConnection();

        $stmt = $conn->prepare($sql);

        for($i = 0; $i < 10; $i++){
            set_time_limit(30);

            if($stmt->execute([$this->writerId, $month, $year])){
                $result = $stmt->fetchAll();
                $result = $result[0];

                if($result == -1){
                    continue;
                }

                if($result == 0){
                    return true;
                }else{
                    return false;
                }
            }
            sleep(1);
        }
        return false;
    }

    public function reportArticle($articleId, $commplaint){

    }
    


 }

?>