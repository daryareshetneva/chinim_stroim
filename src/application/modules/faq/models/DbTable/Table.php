<?php

class Faq_Model_DbTable_Table extends Zend_Db_Table_Abstract{
    
    protected $_name = 'Faq';
    protected $_primary = 'id';
   
    public function getAdminAll(){
        $select = $this->select()
               ->from($this->_name, array('id', 'question', 'answer', 'questionDate', 'userLogin', 'adminLogin', 'show'))
               ->order('questionDate DESC');
       
       $result = $this->getAdapter()->fetchAll($select);
       
       return $result;
    }   
    
    public function getAll($limit, $page){
        $select = $this->select()
               ->from($this->_name, array('question', 'answer', 'questionDate', 'answerDate', 'userLogin', 'adminLogin'))
                ->where('`show` = 1')
               ->limitPage($page, $limit)
               ->order('id DESC');
       
       $result = $this->getAdapter()->fetchAll($select);
       
       return $result;
    }
}