<?php

class Model_DbTable_Managers extends Zend_Db_Table_Abstract{
    
    protected $_name = 'Managers';
    protected $_primary = 'id';
    
    public function getManagers() {
        $select = $this->select()
                ->from($this->_name, array('id', 'email'))
                ->order('id');
        return $this->getAdapter()->fetchAll($select);
    }
 
}