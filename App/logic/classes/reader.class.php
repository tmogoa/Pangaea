<?php

/**
 * This class keeps track of a reader's record and holds the payment methods
 */
 class Reader extends Writer{

    private $recommendedArticles = [];
    private $preferredArticlesTopics = [];
    private $readerSubscriptionLevel;

    public function __construct($writerId = false)
    {
        parent::__construct($writerId);
    }

    public function applaudArticle($articleId){

    }

    public function getReadTimePerArticle($articleId){

    }

    public function paySubscriptionFee($articleId){

    }

    public function reportArticle($articleId, $commplaint){

    }
    

 }

?>