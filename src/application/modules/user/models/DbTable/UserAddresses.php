<?php

class db_UserAddresses {
    // table
    const TABLE = 'UserAddresses';
    // fields
    const _ID = 'id';
    const _USER_ID = 'userId';
    const _CITY_ID = 'cityId';
    const _ADDRESS = 'address';
    const _INDEX = 'INDEX';
}
class User_Model_DbTable_UserAddresses extends Zend_Db_Table_Abstract {

    protected $_name = db_UserAddresses::TABLE;
    protected $_primary = db_UserAddresses::_ID;

    public function getByUserId($userId) {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('addr' => $this->_name), array(db_UserAddresses::_ID, db_UserAddresses::_CITY_ID,
                       db_UserAddresses::_ADDRESS, db_UserAddresses::_INDEX))
                       ->where(db_UserAddresses::_USER_ID . '= ?', $userId)
                       ->join(array('cit' => 'Cities'), 'cit.id = ' . db_UserAddresses::_CITY_ID, array('city'));
        return $this->getAdapter()->fetchAll($select);
    }

}