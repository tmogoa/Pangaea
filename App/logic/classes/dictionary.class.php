<?php
 /**
  * Terms in the dictionary vocabulary array are like this:
  * terms vocabulary=>[term_id, docfreq, termfrequency_in_a_particular_document]
  */
class Dictionary{
    
    private $vocabulary = [];
    private $document;
    private $length;
    private $constructing_index = true;
    private $root_node;

    public function __construct($document)
    {
        $this->root_node = new TermNode(null, 0, 0, 0);
        $this->document = $document;
    }
    
    //This function works during index construction with and id of -1
    public function insertTerm($term, $id, $docFreq = 1, $termFreq = 1, $termExisted = true){
        $term = strtoupper($term);

        //empty the current vocabulary array
         if(count($this->vocabulary) > 0){
            $this->vocabulary = [];
         }

        $current_node = $this->root_node;
        while(true){
            if($term == $current_node->getTerm()){
                if($current_node->getTermFreq() == 0){
                    //we are dealing with a new document  
                    if($this->constructing_index){
                        $current_node->setDocFreq($current_node->getDocFreq()+1);
                    }
                    else{
                        if(!$current_node->getTermExisted() && !$current_node->getIsNew()){
                            $current_node->setDocFreq($current_node->getDocFreq()+1);
                        }
                    }
                    
                }

                $current_node->setTermFreq($current_node->getTermFreq() + 1);
                break;
            } 
            else if($term < $current_node->getTerm()){
                //go left
                if($current_node->leftChild){
                    $current_node = $current_node->leftChild;
                    continue;
                }else{
                    $current_node->leftChild = new TermNode($term, $id, $docFreq, $termFreq);
                    $current_node->leftChild->setTermExisted($termExisted);
                    if(!$this->constructing_index && $id == 0){
                        $current_node->leftChild->setIsNew(true);
                    }
                    break;
                }
            }
            else{
                //go right
                if($current_node->rightChild){
                    $current_node = $current_node->rightChild;
                    continue;
                }else{
                    $current_node->rightChild = new TermNode($term, $id, $docFreq, $termFreq);
                    $current_node->rightChild->setTermExisted($termExisted);
                    if(!$this->constructing_index && $id == 0){
                        $current_node->rightChild->setIsNew(true);
                    }
                    break;
                }
            }

        }

        return;
    }

    //retrieve a node
    public function getTermNode($term){
        $term = strtoupper($term);
        $start_node = $this->root_node;
        while($start_node){

            if($start_node->getTerm() < $term){
                //go right
                $start_node = $start_node->rightChild;
            }
            else if($start_node->getTerm() > $term){
                //go left
                $start_node = $start_node->leftChild;
            }
            else if($start_node->getTerm() == $term){
                //return the node
                return $start_node;
            }

        }

        return false;
    }

    public function getTermId($term){
        $term = strtoupper($term);
        $termNode = $this->getTermNode($term);
        if($termNode){
            return $termNode->getTerm_id();
        }else{
            return -1;
        }
    }

    public function setTermId($term, $id){
        $term = strtoupper($term);
        $termNode = $this->getTermNode($term);
        if($termNode){
            $termNode->setTerm_id($id);
        }else{
            $this->insertTerm($term, $id);
        }
    }

    public function removeTerm($term){
        $term = strtoupper($term);
        $termNode = $this->getTermNode($term);
        
        if($this->root_node->deleteNode($termNode, $this->root_node, 'rightChild')){
            //check if the vocabulary array is already constructed
            $this->vocabulary = [];
        }

    }

    /**
     * Returns the frequency of the term being dealt with
     */
    public function returnTermFreq($term){
        $term = strtoupper($term);
        $termNode = $this->getTermNode($term);
        if($termNode){
            return $termNode->getTermFreq();
        }
        else{
            return false;
        }
    }

    /**
     * This function will not be mostly used since it has O(n) complexity.
     * Returns the number of Term nodes in the dictionary
     * @return int
     */
    public function getDictLength(){
        $this->length = 0;
        $this->performInOrder(function($node){
            if($node->getTerm() !== null){
                $this->length++;
            }  
        }, $this->root_node, $this->root_node);

        return $this->length;
    }

    /**
     * Returns the current length of the dictionary
     * @return int
     */
    public function getVocabLength(){
        return count($this->vocabulary);
    }


    /**
     * Get the value of document.
     * Document is the id of the product that is currently being dealt with
     * Returns a document Id
     * @return int
     */ 
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set the value of document.
     * When the document is set, every term frequency reset to 0
     * @return  self
     */ 
    public function changeDocument($document)
    {
        if($this->document == $document){
            return;
        }
        //reset all term frequency to 0 on document change
        $this->performInOrder(function($node){
            if($node->getTerm()){
                $node->setTermFreq(0);
            }
        }, $this->root_node, $this->root_node);

        $this->document = $document;

        return $this;
    }


    /**
     * Get the vocabulary
     * if the vocabulary is not alreay set, then this function will set it
     * vocabulary=>[term_id, docfreq, termfrequency_in_a_particular_document]
     */ 
    public function getVocabulary()
    {
        if($this->getVocabLength() < 1){
            $this->setVocabulary();
        }
        return $this->vocabulary;
    }

    /**
     * Set the value of vocabulary.
     * [vocabulary]=>[term_id, docfreq, termfrequency_in_a_particular_document]
     * 
     * @return  self
     */ 
    public function setVocabulary()
    {  
             $this->performInOrder(function($currentNode){
                 if($currentNode->getTerm() !== null){
                    $this->vocabulary += array($currentNode->getTerm() => [$currentNode->getTerm_id(), $currentNode->getDocFreq(), $currentNode->getTermFreq()]);
                 }
                
            }, $this->root_node, $this->root_node);

    }

    /**
     * Updates the doc frequency incase a term is no longer found in a document
     * Please call set vocabulary after this function is ran
     */
    public function resetDocFreqs(){
        $this->performInOrder(function($currentNode){
            if($currentNode->getTerm() !== null){
                if($currentNode->getTermFreq() < 1 && !$this->constructing_index && $currentNode->getTermExisted()){
                    $newDocFreq = ($currentNode->getDocFreq() > 0)?$currentNode->getDocFreq() - 1: 0;
                    $currentNode->setDocFreq($newDocFreq);
                }
            }
        }, $this->root_node, $this->root_node);
        
    }

    /**
     * Empty the vocabulary array
     */
    public function emptyVocab(){
        unset($this->vocabulary);
        $this->vocabulary = [];
    }

    /**
     * Peform actions in order on the binary tree
     */
    public function performInOrder($thefunction, $parameter, $termNode){
        if($termNode == null){
            return;
        }
        $tmp_param = $parameter instanceof TermNode? $parameter->leftChild:$parameter;
        $this->performInOrder($thefunction, $tmp_param, $termNode->leftChild);

        $thefunction($parameter);

        $tmp_param = $parameter instanceof TermNode? $parameter->rightChild:$parameter;
        $this->performInOrder($thefunction, $tmp_param, $termNode->rightChild);
    }

    /**
     * Prints the dictionary in order
     * @return void
     */
    public function printInorder(){
        $this->performInOrder(function($node){
            if($node->getTerm() !== null){
                echo "{Term: {$node->getTerm()}, DocFreq: {$node->getDocFreq()}, TermFreq: {$node->getTermFreq()}}->";
            }
        }, $this->root_node, $this->root_node);
    }

    /**
     * Get the value of root_node
     */ 
    public function getRoot_node()
    {
        return ($this->root_node->rightChild)?$this->root_node->rightChild:false;
    }

    /**
     * Get the value of constructing_index
     */ 
    public function isConstructingIndex()
    {
        return $this->constructing_index;
    }

    /**
     * Set the value of constructing_index
     *
     * @return  self
     */ 
    public function setConstructingIndex($constructing_index)
    {
        $this->constructing_index = $constructing_index;

        return $this;
    }

}

?>