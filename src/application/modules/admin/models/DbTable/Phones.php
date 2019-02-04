<?php

class Admin_Model_DbTable_Phones extends Zend_Db_Table_Abstract{
    
    protected $_name = 'Phones';
    protected $_primary = 'id';
    

    public function getAll() {
        $select = $this->select()
            ->from($this->_name, ['id', 'phone', 'isMain'])
            ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getMainPhones() {
        $select = $this->select()
            ->from($this->_name, ['id', 'phone', 'isMain'])
            ->where('isMain = 1')
            ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }

}