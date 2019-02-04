<?php

class Payment_Model_DbTable_TrxHistory extends Zend_Db_Table_Abstract {

    protected $_name = 'TrxHistory';
    protected $_primary = 'id';
    protected $_fields = array(
        'id', 'type', 'userId', 'userFio', 'userEmail',
        'orderId', 'deliveryId', 'trxId', 'amount', 'ts'
    );

    public function getAll() {
        $select = $this->select()
                ->from($this->_name, $this->_fields)
                ->order('id DESC');
        return $this->getAdapter()->fetchAll($select);
    }

    public function getByUserId($userId) {
        $select = $this->select()
                ->from($this->_name, $this->_fields)
                ->where('userId = ?', $userId)
                ->order('ts DESC');
        return $this->getAdapter()->fetchAll($select);
    }

}
