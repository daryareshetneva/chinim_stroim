<?php

class Model_DbTable_Modules extends Zend_Db_Table_Abstract{
    
    protected $_name = 'Modules';
    protected $_primary = 'name';
    
    public function getModule($name) {
        $select = $this->select()
                ->from($this->_name, array('name', 'version'))
                ->where('name=?', $name);
        
        $result = $this->getAdapter()->fetchRow($select);
        
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }
    public function getModuleIdByName($name) {
        $select = $this->select()
            ->from($this->_name, array('id'))
            ->where('name = ?', $name);

        return $this->getAdapter()->fetchRow($select);
    }
    
}
    