<?php

class Portfolio_Model_DbTable_PortfolioComments extends Zend_Db_Table_Abstract {
    
    protected $_name = 'PortfolioComments';
    protected $_primary = 'id';
   
    public function getAll() {
        $select = $this->select()
                ->from($this->_name, array('id', 'username', 'email', 'status', 'date', 'comment', 'username', 'email'))
                ->order('date DESC');
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function countNewComments() {
        $select = $this->select()
                ->from($this->_name, array('COUNT(*)'))
                ->where('new = 1');
        return $this->getAdapter()->fetchOne($select);
    }
    
    public function getCommentsByPortfolioId($workId) {
        $select = $this->select()
                ->from($this->_name, array('id', 'username', 'comment', 'reply', 'date'))
                ->where('status = 1')
                ->where('portfolioId = ?', $workId)
                ->order('date');
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getByPortfolioId($workId) {
        $select = $this->select()
                ->from($this->_name, array('id', 'username', 'comment', 'reply', 'status', 'date', 'email'))
                ->where('portfolioId = ?', $workId)
                ->order('date');
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getPairs() {
        $select = $this->select()
                ->from($this->_name, array('id', 'portfolioId'));
        return $this->getAdapter()->fetchPairs($select);
    }
}
