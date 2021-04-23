<?php
/**
 * This class represent terms in the dictionary
 */
class TermNode{
    private $term, $docFreq, $termFreq, $term_id, $is_new, $termExisted = false;
    public $leftChild = null, $rightChild = null;
    
    public function __construct($term, $term_id, $docFreq, $termFreq, $is_new = false)
    {
        
        $this->term = $term;
        $this->term_id = $term_id;
        $this->docFreq = $docFreq;
        $this->termFreq = $termFreq;
        $this->is_new = $is_new;
    }

    /**
     * Get the value of term
     */ 
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set the value of term
     *
     * @return  self
     */ 
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get the value of docFreq
     */ 
    public function getDocFreq()
    {
        return $this->docFreq;
    }

    /**
     * Set the value of docFreq
     *
     * @return  self
     */ 
    public function setDocFreq($docFreq)
    {
        $this->docFreq = $docFreq;

        return $this;
    }

    /**
     * Get the value of termFreq
     */ 
    public function getTermFreq()
    {
        return $this->termFreq;
    }

    /**
     * Set the value of termFreq
     *
     * @return  self
     */ 
    public function setTermFreq($termFreq)
    {
        $this->termFreq = $termFreq;

        return $this;
    }

    /**
     * Get the value of term_id
     */ 
    public function getTermId()
    {
        return $this->term_id;
    }

    /**
     * Set the value of term_id
     *
     * @return  self
     */ 
    public function setTermId($term_id)
    {
        $this->term_id = $term_id;

        return $this;
    }

    public function deleteNode($termNode, TermNode $parent = null, $left_right=''){
        if($termNode->getTerm() > $this->term){
            return $this->rightChild && $this->rightChild->deleteNode($termNode, $this, 'rightChild');
        }
        else if ($termNode->getTerm() < $this->term){
            return $this->leftChild && $this->leftChild->deleteNode($termNode, $this, 'leftChild');
        }else{
            // found my search node
            if ($this->leftChild) {
                // promote the left node
                $parent->$left_right = $this->leftChild;
                $this->rightChild && $this->leftChild->insert($this->rightChild);
            } else if ($this->rightChild) {
                // promote the right node
                $parent->$left_right = $this->rightChild;
                $this->leftChild && $this->rightChild->insert($this->leftChild);
            } else {
                // leaf node
                $parent->$left_right = null;
            }
            return true;
        }
    
    }

    public function insert($node) {
		if ($node->getTerm() > $this->term) {
			$this->rightChild ? $this->rightChild->insert($node) : ($this->rightChild = $node);
		} elseif ($node->getTerm() < $this->term) {
			$this->leftChild ? $this->leftChild->insert($node) : ($this->leftChild = $node);
		} else {
			// found duplicate node
			return;
		}
	}
    

    /**
     * Get the value of is_new
     */ 
    public function getIsNew()
    {
        return $this->is_new;
    }

    /**
     * Set the value of is_new
     *
     * @return  self
     */ 
    public function setIsNew($is_new)
    {
        $this->is_new = $is_new;

        return $this;
    }

    

    /**
     * Get the value of termExisted
     */ 
    public function getTermExisted()
    {
        return $this->termExisted;
    }

    /**
     * Set the value of termExisted
     *
     * @return  self
     */ 
    public function setTermExisted($termExisted)
    {
        $this->termExisted = $termExisted;

        return $this;
    }
}
?>