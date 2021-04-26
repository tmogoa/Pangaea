<?php
spl_autoload_register(function($name){
  require_once("$name.class.php");
});
class Indexer {
//For binary searching array with numerical index

public function binarySearch($array, $what){
    $high = count($array)-1;
    $low = 0;
    

    while ($high >= $low){
        $mid = (int) (($high + $low)/2);
        if($array[$mid] > $what){
            $high = $mid - 1;
        }
        else if($array[$mid] < $what){
            $low = $mid + 1;
        }
        else{
            return $mid;
        }
    }

    return -1;
}


/**
 * Constructs the index from scratch.
 * This function is optimize to use less resources, however, more optimization is needed
 * Only pass the the id and the keywords if the product is being update. 
 * otherwise, use 0 and null
 */
public function constructIndex($first_doc_id = 0, array $keywords = null, &$conn = false){

    $was_passed = ($conn !== false)?true:false;
    if(!$was_passed){
       $conn = Utility::makeConnection();
     }

    //get the current dictionary
    $dictionary_terms = Utility::queryTable("index", "termId, term, docfreq", "1 = ? order by term ASC",[1], $conn);
    if($first_doc_id > 0){
        Utility::addColumn($first_doc_id, 'INT NOT NULL DEFAULT 0', 'index', $conn);
        $dictionary_terms = Utility::queryTable("index", "termId, term, docfreq, `$first_doc_id`", "1 = ? order by term ASC", [1], $conn);
    }else{
        $dictionary_terms = Utility::queryTable("index", "termId, term, docfreq", "1 = ? order by term ASC", [1], $conn);
    }

    //since the dictionary is a binary tree, insert the terms into the dictionary
    $dictionary = new Dictionary($first_doc_id);
    if($dictionary_terms){
        $this->InsertIntoDictionary($dictionary, $dictionary_terms);
        //The dictionary_terms has been deleted
    }
    
    //index in block
    $start = 0;
    $end = 20;

    if($first_doc_id > 0){
        $specificArticle = " AND articleId = ?";
        $_keywords = "";
    }else{
        $specificArticle = " LIMIT ?, ?";
        $_keywords = ", keywords ";
    }
    $stmt = $conn->prepare("SELECT articleId $_keywords from articleKeywords where is_indexed = ? $specificArticle");

    if($first_doc_id < 1){
      echo "SELECT articleId $_keywords from articleKeywords where is_indexed = ? $specificArticle \n";
      $stmt->execute([0, $start, $end]);
      $unindexed_articles = $stmt->fetchAll(); 
    }else{
      $stmt->execute([0, $first_doc_id]);
      $unindexed_articles = $stmt->fetchAll();
    }
    


    if($unindexed_articles){
        do{
            //index all unindexed products
            if($first_doc_id > 0){
                //print_r($keywords);
                $this->indexAnArticle($keywords, $dictionary, $conn, true);
                
            }else{
                foreach($unindexed_articles as $u_article){
                    $articleId = $u_article['articleId'];
                    $keywords = $u_article['keywords'];
                    $keywords = explode(" ", $keywords);

                    $dictionary->changeDocument($articleId);

                    $this->indexAnArticle($keywords, $dictionary, $conn);

                }
            }
            

            if($first_doc_id < 1){
                $start += $end;
                $stmt->execute([0, $first_doc_id]);
                $unindexed_articles = $stmt->fetchAll();
            } 

        }while(count($unindexed_articles) > 0 && $first_doc_id < 1);
    }
    //indexing is completed
    return;
}

/**
 * Inserts a sorted term of data into a binary tree, here the dictionary
 * The array is deleted after insertion
 */
public function InsertIntoDictionary(Dictionary &$dictionary, array &$terms)
 {
    $size = count($terms);

    if($size < 1){
        //for consistensy
        unset($terms);
        return;
    }

    $jump_factor = 2 ** floor(log($size, 2)); // jump_factor will select which elements get inserted.
    $prev_jf = $jump_factor; //previous jump_factor will take note of the last jump factor so that
    //no element get inserted twice.

    $articleId = $dictionary->getDocument();
    do{
        for($i = $jump_factor; ($i - 1) < $size; $i += $prev_jf){
            $terms_details = $terms[$i - 1];

            //unset the index to maintain space complexity
            unset($terms[$i - 1]);
            
            //insert into Binary tree logn complexity
            if(isset($terms_details["$articleId"]) && $terms_details["$articleId"] > 0){
                $term_existed = true;
            }else{
                $term_existed = false;
            }
            $dictionary->insertTerm($terms_details['term'], $terms_details['termId'], $terms_details['docfreq'], 0, $term_existed);
        }
        //update jump and previous jumpfactor
        $prev_jf = $jump_factor;
        $jump_factor /= 2;

    }while($prev_jf > 1);
   return;
 }

 /**
  * Index a product
  * This function assumes that the keywords has already being sanitized with the stop words removed
  */
 public function indexAnArticle(array $keywords, Dictionary &$dictionary, &$conn = false, $updateDocFreq = false){
    
    if($updateDocFreq){
        $dictionary->setConstructingIndex(false);
    }

    foreach($keywords as $key => $keyword){
        //there are already terms in the dictionary with their own ids, that is why 0 is passed as the id.
        $dictionary->insertTerm($keyword, 0, 1, 1);
        unset($keywords[$key]);
    }
    //set the vocabulary array for insertion
    // echo "\nDictionary in indexAnArticle\n";
    //$dictionary->printInorder();
    if($updateDocFreq){
        $dictionary->resetDocFreqs();
    }

    
    $articleId = $dictionary->getDocument();

    

    $was_passed = ($conn !== false)?true:false;
     if(!$was_passed){
        $conn = Utility::makeConnection();
      }
     

    if(Utility::addColumn($articleId, 'INT NOT NULL DEFAULT 0', 'index', $conn)){

        $InsertTermSql = "INSERT INTO `index`(term, docfreq, `$articleId`) values(?, ?, ?)";
        $updateTermSql = "UPDATE `index` set docfreq = ?, `$articleId` = ? where termId = ?";
        $deleteTermSql = "DELETE from `index` where termId = ?";

        $InsertStmt = $conn->prepare($InsertTermSql);
        $updateStmt = $conn->prepare($updateTermSql);
        $deleteStmt = $conn->prepare($deleteTermSql);
        
        $this->performIndexing($dictionary->getRoot_node(), $dictionary, $conn, $InsertStmt, $updateStmt, $deleteStmt, $updateDocFreq);
        
        //update is index
        Utility::updateTable("articleKeywords", "is_indexed = ?", "articleId = ?",[1, $articleId], $conn);
        return;
    }

  }

  //Recursively performs indexing
 public function performIndexing($termNode, $dictionary, $conn, $InsertStmt, $updateStmt, $deleteStmt, $updateDocFreq){
    if($termNode == null){
        return;
    }
    $this->performIndexing($termNode->leftChild, $dictionary, $conn, $InsertStmt, $updateStmt, $deleteStmt, $updateDocFreq);

    $term = $termNode->getTerm();
    $termDocFreq = $termNode->getDocFreq();
    $termFreq = $termNode->getTermFreq();
    $termId = $termNode->gettermId();
    
    if($termDocFreq > 0){
        if($termId == 0){
            //term id
            //echo "\n\nAdding term: $term \n\n";

           $InsertStmt->execute([$term, $termDocFreq, $termFreq]);
           $termNode->settermId($conn->lastInsertId());
        }
        else{
           // echo "\n\nThe TermFreq SQL IS: $termFreq \n\n";
            $updateStmt->execute([$termDocFreq, $termFreq, $termId]);
        }
    }else if($updateDocFreq){
        //the term is useless
        if($deleteStmt->execute([$termId])){
            //echo "\nDeleted Term: $term\n";
            //remove it from the dictionary
            $dictionary->removeTerm($term);
        }
    }
    $this->performIndexing($termNode->rightChild, $dictionary, $conn, $InsertStmt, $updateStmt, $deleteStmt, $updateDocFreq);  
  }
}
  
// $indexer = new Indexer();
// $indexer->constructIndex(0);
 
?>

