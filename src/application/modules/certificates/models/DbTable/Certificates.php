<?php

class Certificates_Model_DbTable_Certificates extends Zend_Db_Table_Abstract{

    public $_primary = 'id';
    public $_name = 'Certificates';

    public function getAll($order = 'date') {
        $select = $this->select()
            ->from($this->_name, ['id', 'title', 'image', 'description'])
            ->order($order);
        return $this->getAdapter()->fetchAll($select);
    }
}