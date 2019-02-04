<?php

class Payment_Model_TrxHistory {

    // dbTable
    const DB_ID = 'id';
    const DB_TYPE = 'type';
    const DB_USER_ID = 'userId';
    const DB_USER_FIO = 'userFio';
    const DB_USER_EMAIL = 'userEmail';
    const DB_ORDER_ID = 'orderId';
    const DB_DELIVERY_ID = 'deliveryId';
    const DB_TRX_ID = 'trxId';
    const DB_AMOUNT = 'amount';
    const DB_TS = 'ts';
    // trx
    const TRX_ID = 'id';
    const TRX_USER_ID = 'userId';
    const TRX_USER_FIO = 'userFio';
    const TRX_USER_EMAIL = 'userEmail';
    const TRX_ORDER_ID = 'orderId';
    const TRX_DELIVERY_ID = 'deliveryId';
    const TRX_AMOUNT = 'amount';
    const TRX_TS = 'ts';
    // type
    const TYPE_ADD = 'add';
    const TYPE_HOLDADD = 'hold-add';
    const TYPE_HOLDDEC = 'hold-dec';
    const TYPE_DEC = 'dec';
    const TYPE_ORDER = 'order';

    protected $_dbTable;

    public function __construct() {
        $this->_dbTable = new Payment_Model_DbTable_TrxHistory();
    }

// ============================
// ========== PUBLIC ==========
// ============================
    
    public function addRowBalanceAddAdmin($summ, $user) {
        $data = array(
            self::DB_TYPE => self::TYPE_ADD,
            self::DB_USER_ID => $user[db_Users::_ID],
            self::DB_USER_FIO => $user[db_Users::_FIO],
            self::DB_USER_EMAIL => $user[db_Users::_EMAIL],
            self::DB_AMOUNT => $summ,
            self::DB_TS => date(DATE_W3C, time())
        );
        $this->_dbTable->insert($data);
    }

    public function addRowBalanceDecAdmin($summ, $user) {
        $data = array(
            self::DB_TYPE => self::TYPE_DEC,
            self::DB_USER_ID => $user[db_Users::_ID],
            self::DB_USER_FIO => $user[db_Users::_FIO],
            self::DB_USER_EMAIL => $user[db_Users::_EMAIL],
            self::DB_AMOUNT => $summ,
            self::DB_TS => date(DATE_W3C, time())
        );
        $this->_dbTable->insert($data);
    }

    public function addRowBalanceTransaction($trx) {
        $trx = $this->prepareTrx($trx);
        $this->insert($trx, self::TYPE_ADD);
    }
    
    public function addRowOrderTransaction($trx) {
        $trx = $this->prepareTrx($trx);
        $this->insert($trx, self::TYPE_DEC);
    }

    public function addRowDeliveryTransaction($trx) {
        $trx = $this->prepareTrx($trx);
        $this->insert($trx, self::TYPE_DEC);
    }

    public function addRowOrderBalance($user, $orderId, $amount) {
        $this->insert(
            array(
                self::TRX_USER_ID => $user[db_Users::_ID],
                self::TRX_USER_FIO => $user[db_Users::_FIO],
                self::TRX_USER_EMAIL => $user[db_Users::_EMAIL],
                self::TRX_ORDER_ID => $orderId,
                self::TRX_AMOUNT => $amount
            ),
            self::TYPE_DEC
        );
    }
    
    public function addRowDeliveryBalance($user, $deliveryId, $amount) {
        $this->insert(
            array(
                self::TRX_USER_ID => $user[db_Users::_ID],
                self::TRX_USER_FIO => $user[db_Users::_FIO],
                self::TRX_USER_EMAIL => $user[db_Users::_EMAIL],
                self::TRX_DELIVERY_ID => $deliveryId,
                self::TRX_AMOUNT => $amount
            ),
            self::TYPE_DEC
        );
    }
    
    public function addRow($user, $amount, $type, $data = array()) {
        $data = array(
            self::DB_TYPE => $type,
            self::DB_USER_ID => $user[db_Users::_ID],
            self::DB_USER_FIO => $user[db_Users::_FIO],
            self::DB_USER_EMAIL => $user[db_Users::_EMAIL],
            self::DB_TRX_ID =>
                isset($data[self::TRX_ID])
                    ? $data[self::TRX_ID]
                    : null,
            self::DB_ORDER_ID =>
                isset($data[self::TRX_ORDER_ID])
                    ? $data[self::TRX_ORDER_ID]
                    : null,
            self::TRX_DELIVERY_ID =>
                isset($data[self::TRX_DELIVERY_ID])
                    ? $data[self::TRX_DELIVERY_ID]
                    : null,
            self::DB_AMOUNT => $amount,
            self::DB_TS => date(DATE_W3C, time())
        );
        $this->_dbTable->insert($data);
    }
    
    public function addRowTrx($type, $trx) {
        $trx = $this->prepareTrx($trx);
        $this->insert($trx, $type);
    }

// ============================
// ========= PROTECTED ========
// ============================
    protected function prepareTrx($trx) {
        $userModel = new User_Model_Users();
        $user = $userModel->getUserById($trx[self::TRX_USER_ID]);
        $trx[self::TRX_USER_FIO] = $user[db_Users::_FIO];
        $trx[self::TRX_USER_EMAIL] = $user[db_Users::_EMAIL];
        
        return $trx;
    }
    
    protected function insert($data, $type) {
        $data = array(
            self::DB_TYPE => $type,
            self::DB_USER_ID => $data[self::TRX_USER_ID],
            self::DB_USER_FIO => $data[self::TRX_USER_FIO],
            self::DB_USER_EMAIL => $data[self::TRX_USER_EMAIL],
            self::DB_TRX_ID =>
                isset($data[self::TRX_ID])
                    ? $data[self::TRX_ID]
                    : null,
            self::DB_ORDER_ID =>
                isset($data[self::TRX_ORDER_ID])
                    ? $data[self::TRX_ORDER_ID]
                    : null,
            self::TRX_DELIVERY_ID =>
                isset($data[self::TRX_DELIVERY_ID])
                    ? $data[self::TRX_DELIVERY_ID]
                    : null,
            self::DB_AMOUNT => $data[self::TRX_AMOUNT],
            self::DB_TS => date(DATE_W3C, time())
        );
        $this->_dbTable->insert($data);
    }

}
