<?php

class db_Cities {
    // table
    const TABLE = 'Cities';
    // fields
    const _ID = 'id';
    const _CITY = 'city';
}
class User_Model_DbTable_Cities extends Zend_Db_Table_Abstract {

    protected $_name = db_Cities::TABLE;
    protected $_primary = db_Cities::_ID;

    public function getPairs() {
        $select = $this->select()
                       ->from($this->_name, array(db_Cities::_ID, db_Cities::_CITY))
                       ->order(db_Cities::_CITY);
        return $this->getAdapter()->fetchPairs($select);
    }

    public function getIdByCity($city) {
        $select = $this->select()
                       ->from($this->_name, array(db_Cities::_ID))
                       ->where(db_Cities::_CITY . " = ?", $city);
        return $this->getAdapter()->fetchOne($select);
    }
}