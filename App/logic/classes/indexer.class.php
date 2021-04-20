<?php

// class Indexer {
// //For binary searching array with numerical index

// public function binarySearch($array, $what){
//     $high = count($array)-1;
//     $low = 0;
    

//     while ($high >= $low){
//         $mid = (int) (($high + $low)/2);
//         if($array[$mid] > $what){
//             $high = $mid - 1;
//         }
//         else if($array[$mid] < $what){
//             $low = $mid + 1;
//         }
//         else{
//             return $mid;
//         }
//     }

//     return -1;
// }


// /**
//  * Constructs the index from scratch.
//  * This function is optimize to use less resources, however, more optimization is needed
//  * Only pass the the id and the keywords if the product is being update. 
//  * otherwise, use 0 and null
//  */
// public function constructIndex($first_doc_id = 0, array $keywords = null, &$conn = false){

//     $was_passed = ($conn !== false)?true:false;
//     if(!$was_passed){
//        $conn = Utility::makeConnection();
//      }

//     //get the current dictionary
//     $dictionary_terms = Utility::queryTable("inverted_index", "term_id, term, docfreq", "1 order by term ASC", $conn);
//     if($first_doc_id > 0){
//         Utility::addColumn($first_doc_id, 'INT NOT NULL DEFAULT 0', 'inverted_index', $conn);
//         $dictionary_terms = Utility::queryTable("inverted_index", "term_id, term, docfreq, `$first_doc_id`", "1 order by term ASC", $conn);
//     }else{
//         $dictionary_terms = Utility::queryTable("inverted_index", "term_id, term, docfreq", "1 order by term ASC", $conn);
//     }

//     //since the dictionary is a binary tree, insert the terms into the dictionary
//     $dictionary = new Dictionary($first_doc_id);
//     if($dictionary_terms){
//         $this->InsertIntoDictionary($dictionary, $dictionary_terms);
//         //The dictionary_terms has been deleted
//     }
    
//     //index in block
//     $start = 0;
//     $end = 20;

//     if($first_doc_id > 0){
//         $specificProduct = " AND product_id = $first_doc_id";
//         $_keywords = "";
//     }else{
//         $specificProduct = " LIMIT ?, ?";
//         $_keywords = ", keywords ";
//     }
//     $stmt = $conn->prepare("SELECT product_id $_keywords from product where is_indexed = 0 $specificProduct");

//     if($first_doc_id < 1){
//         $stmt->bind_param('ii', $start, $end);
//     }
    
//     $stmt->execute();
//     $unindexed_products = $stmt->get_result();

//     if($unindexed_products->num_rows){
//         do{
//             //index all unindexed products
//             if($first_doc_id > 0){
//                 //print_r($keywords);
//                 indexAProduct($keywords, $dictionary, $conn, true);
                
//             }else{
//                 while($u_product = $unindexed_products->fetch_assoc()){
//                     $product_id = $u_product['product_id'];
//                     $keywords = $u_product['keywords'];
//                     $keywords = explode(" ", $keywords);

//                     $dictionary->changeDocument($product_id);

//                     indexAProduct($keywords, $dictionary, $conn);

//                 }
//             }
            

//             if($first_doc_id < 1){
//                 $start += $end;
//                 $stmt->bind_param('ii', $start, $end);
//                 $stmt->execute();
//                 $unindexed_products = $stmt->get_result();
//             } 

//         }while($unindexed_products->num_rows > 0 && $first_doc_id < 1);
//     }

//     (!$was_passed)?$conn->close():"";
//     //indexing is completed
//     return;

// }

// /**
//  * Inserts a sorted term of data into a binary tree, here the dictionary
//  * The array is deleted after insertion
//  */
// public function InsertIntoDictionary(Dictionary &$dictionary, array &$terms)
//  {
//     $size = count($terms);

//     if($size < 1){
//         //for consistensy
//         unset($terms);
//         return;
//     }

//     $jump_factor = 2 ** floor(log($size, 2)); // jump_factor will select which elements get inserted.
//     $prev_jf = $jump_factor; //previous jump_factor will take note of the last jump factor so that
//     //no element get inserted twice.

//     $product_id = $dictionary->getDocument();
//     do{
//         for($i = $jump_factor; ($i - 1) < $size; $i += $prev_jf){
//             $terms_details = $terms[$i - 1];

//             //unset the index to maintain space complexity
//             unset($terms[$i - 1]);
            
//             //insert into Binary tree logn complexity
//             if(isset($terms_details["$product_id"]) && $terms_details["$product_id"] > 0){
//                 $term_existed = true;
//             }else{
//                 $term_existed = false;
//             }
//             $dictionary->insertTerm($terms_details['term'], $terms_details['term_id'], $terms_details['docfreq'], 0, $term_existed);
//         }
//         //update jump and previous jumpfactor
//         $prev_jf = $jump_factor;
//         $jump_factor /= 2;

//     }while($prev_jf > 1);
//    return;
//  }

//  /**
//   * Index a product
//   * This function assumes that the keywords has already being sanitized with the stop words removed
//   */
//  public function indexAProduct(array $keywords, Dictionary &$dictionary, &$conn = false, $updateDocFreq = false){
    
//     if($updateDocFreq){
//         $dictionary->setConstructingIndex(false);
//     }

//     foreach($keywords as $key => $keyword){
//         //there are already terms in the dictionary with their own ids, that is why 0 is passed as the id.
//         $dictionary->insertTerm($keyword, 0, 1, 1);
//         unset($keywords[$key]);
//     }
//     //set the vocabulary array for insertion
//     // echo "\nDictionary in IndexAProduct\n";
//     //$dictionary->printInorder();
//     if($updateDocFreq){
//         $dictionary->resetDocFreqs();
//     }

    
//     $product_id = $dictionary->getDocument();

    

//     $was_passed = ($conn !== false)?true:false;
//      if(!$was_passed){
//         $conn = makeConnection();
//       }
     

//     if(addColumn($product_id, 'INT NOT NULL DEFAULT 0', 'inverted_index', $conn)){

//         $InsertTermSql = "INSERT INTO inverted_index(term, docfreq, `$product_id`) values(?, ?, ?)";
//         $updateTermSql = "UPDATE inverted_index set docfreq = ?, `$product_id` = ? where term_id = ?";
//         $deleteTermSql = "DELETE from inverted_index where term_id = ?";

//         $InsertStmt = $conn->prepare($InsertTermSql);
//         $updateStmt = $conn->prepare($updateTermSql);
//         $deleteStmt = $conn->prepare($deleteTermSql);
        
//         performIndexing($dictionary->getRoot_node(), $dictionary, $conn, $InsertStmt, $updateStmt, $deleteStmt, $updateDocFreq);
        
//         //update is index
//         updateTable("product", "is_indexed = 1", "product_id = $product_id", $conn);
//         (!$was_passed)?$conn->close():"";
//         return;
//     }

//   }

//   //Recursively performs indexing
//  public function performIndexing($termNode, $dictionary, $conn, $InsertStmt, $updateStmt, $deleteStmt, $updateDocFreq){
//     if($termNode == null){
//         return;
//     }
//     performIndexing($termNode->leftChild, $dictionary, $conn, $InsertStmt, $updateStmt, $deleteStmt, $updateDocFreq);

//     $term = $termNode->getTerm();
//     $termDocFreq = $termNode->getDocFreq();
//     $termFreq = $termNode->getTermFreq();
//     $term_id = $termNode->getTerm_id();
    
//     if($termDocFreq > 0){
//         if($term_id == 0){
//             //term id
//             //echo "\n\nAdding term: $term \n\n";
//            $InsertStmt->bind_param('sii', $term, $termDocFreq, $termFreq);

//            $InsertStmt->execute();
//            $termNode->setTerm_id($conn->insert_id);
//         }
//         else{
//            // echo "\n\nThe TermFreq SQL IS: $termFreq \n\n";
//             $updateStmt->bind_param('iii', $termDocFreq, $termFreq, $term_id);
//             $updateStmt->execute();
//         }
//     }else if($updateDocFreq){
//         //the term is useless
//         $deleteStmt->bind_param('i', $term_id);
//         if($deleteStmt->execute()){
//             //echo "\nDeleted Term: $term\n";
//             //remove it from the dictionary
//             $dictionary->removeTerm($term);
//         }
//     }
//     performIndexing($termNode->rightChild, $dictionary, $conn, $InsertStmt, $updateStmt, $deleteStmt, $updateDocFreq);  
//   }
// }
  //constructIndex(0);
 
?>

