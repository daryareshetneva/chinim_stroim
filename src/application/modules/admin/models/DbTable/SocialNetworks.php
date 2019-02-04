<?php

class Admin_Model_DbTable_SocialNetworks extends Zend_Db_Table_Abstract{
    
    protected $_name = 'SocialNetworks';
    protected $_primary = 'id';
    

    public function getAll() {
        $select = $this->select()
            ->from($this->_name, ['id', 'title', 'url', 'img'])
            ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getByTitle($title) {
        $select = $this->select()
            ->from($this->_name, ['id', 'title', 'url', 'img'])
            ->where('title = ?', $title);
        return $this->getAdapter()->fetchAll($select);
    }

}