<?php

class db_UserCars {
    // table
    const TABLE = 'UserCars';
    // fields
    const _ID = 'id';
    const _USER_ID = 'userId';
    const _VEHICLE = 'vehicle';
    const _MODEL = 'model';
    const _VIN = 'VIN';
    const _YEAR = 'year';
    const _ENGINE_VOL = 'engineVol';
    const _DRIVE = 'drive';
    const _POWER = 'power';
    // enums
    const DRIVE_FULL = '4x4';
    const DRIVE_FRONT = 'f';
    const DRIVE_BACK = 'b';
}

class User_Model_DbTable_UserCars extends Zend_Db_Table_Abstract {
    // vars
    protected $_name = db_UserCars::TABLE;
    protected $_primary = db_UserCars::_ID;
    protected $_fields = array( db_UserCars::_ID, db_UserCars::_USER_ID, db_UserCars::_VEHICLE,
        db_UserCars::_MODEL, db_UserCars::_VIN, db_UserCars::_YEAR, db_UserCars::_ENGINE_VOL,
        db_UserCars::_DRIVE, db_UserCars::_POWER );

    /**
     * Get cars list for user 
     * @param int $userId
     * @return array cars
     */
    public function getCars( $userId ) {
        $select = $this->select()
                ->from( $this->_name, $this->_fields )
                ->where( db_UserCars::_USER_ID . ' = ?', $userId )
                ->order( db_UserCars::_YEAR );
        return $this->getAdapter()->fetchAll( $select );
    }

    /**
     * Get car 
     * @param int $id Car id
     * @return array car fields
     */
    public function getCar( $id ) {
        $select = $this->select()
                ->from( $this->_name, $this->_fields )
                ->where( db_UserCars::_ID . ' = ?', $id );
        return $this->getAdapter()->fetchRow( $select );
    }

    /**
     * update car 
     * @param int $id Car id
     * @param array $data
     */
    public function update( array $data, $id ) {
        $where = $this->getAdapter()->quoteInto( db_UserCars::_ID . ' = ?', $id );
        parent::update( $data, $where );
    }

}