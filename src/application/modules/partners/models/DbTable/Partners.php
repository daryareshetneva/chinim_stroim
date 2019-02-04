<?php

class Partners_Model_DbTable_Partners extends Zend_Db_Table_Abstract{

    public $_primary = 'id';
    public $_name = 'Partners';
    
    public function getAll() {
        $select = $this->select()
            ->from($this->_name, ['id', 'title', 'photo', 'url'])
            ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }
}