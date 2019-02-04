<?php

class Portfolio_Model_DbTable_PortfolioCatalogs extends Zend_Db_Table_Abstract {

    protected $_name = 'PortfolioCatalogs';
    protected $_primary = 'id';

    public function getAll() {
        $select = $this->select()
            ->from($this->_name, array('id', 'title'))
            ->order('id');

        return $this->getAdapter()->fetchAll($select);
    }

    public function getPairs() {
        $select = $this->select()
            ->from($this->_name, array('id', 'title'))
            ->order('id');
        return $this->getAdapter()->fetchPairs($select);
    }

    public function updateAliasById($alias, $id){
        $data = [
            'alias' => $alias
        ];
        $where = [
            'id = ?' => $id
        ];
        $this->update($data, $where);
    }
}
