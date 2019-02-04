<?php

class Specialists_Model_DbTable_Specialists extends Zend_Db_Table_Abstract{

    public $_primary = 'id';
    public $_name = 'Specialists';
    
    public function getAll($order = 'position') {
        $select = $this->select()
            ->from($this->_name, ['id', 'fio', 'post', 'photo', 'shortDescription'])
            ->order($order);
        return $this->getAdapter()->fetchAll($select);
    }
}