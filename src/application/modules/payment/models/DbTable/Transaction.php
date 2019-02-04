<?php

class Payment_Model_DbTable_Transaction extends Zend_Db_Table_Abstract {

    protected $_name = 'Transaction';
    protected $_primary = 'id';
    protected $_fields = array(
        'id', 'status', 'type', 'userId', 'orderId', 'deliveryId', 'amount', 'description',
        'bankAmount', 'bankTrxId', 'bankLangCode', 'bankRrn', 'bankAuthCode',
        'bankTrxDate', 'bankMaskedPan', 'bankCardholder', 'bankTs', 'ts'
    );
    
    /**
     * Insert after init payment
     * <$insert> array:
     * 
     * 'orderId' => int, 'status' => str, 'userId' => int, 'userAmount' => float
     * 
     * </$insert>
     */
    public function trxInsert( $insert ) {
        $insert[ 'ts' ] = date( DATE_W3C, time() );
        return $this->insert( $insert );
    }

    /**
     * Insert after init payment
     * <$update> array:
     * 'bankTrxId' => int,
     * 'status' => str,
     * 'bankLangCode' => int,
     * 'bankTs' => time
     * </$update>
     */
    public function trxUpdate( $update, $trxId ) {
        $update[ 'ts' ] = date( DATE_W3C, time() );
        $where = $this->getAdapter()->quoteInto( 'id = ?', $trxId );
        $this->update( $update, $where );
    }

    public function trxGet( $trxId ) {
        $select = $this->select()
                ->from( $this->_name, $this->_fields )
                ->where( 'id = ?', $trxId );
        return $this->getAdapter()->fetchRow( $select );
    }
    
    public function trxGetAll() {
        $select = $this->select()
                ->from( $this->_name, $this->_fields )
                ->order( 'id DESC' );
        return $this->getAdapter()->fetchAll( $select );
    }
    
    public function trxGetByOrderId( $orderId ) {
        $select = $this->select()
                ->from( $this->_name, $this->_fields )
                ->where( 'orderId = ?', $orderId );
        return $this->getAdapter()->fetchRow( $select );
    }
    
    public function trxGetByOrderIdToRepeat( $orderId ) {
        $select = $this->select()
                ->from( $this->_name, $this->_fields )
                ->where( '`orderId` = ?', $orderId )
                ->where( '`status` != ?', 'cancel');
        return $this->getAdapter()->fetchRow( $select );
    }

    public function startTrasnaction() {
        $this->_db->beginTransaction();
    }

    public function commitTrasnaction() {
        $this->_db->commit();
    }

    public function rollbackTrasnaction() {
        $this->_db->rollBack();
    }

}