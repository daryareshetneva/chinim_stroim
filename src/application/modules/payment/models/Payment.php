<?php

class Payment_Model_Payment {
    // init
    const BANK_INIT_URI = 'https://www.pps.gazprombank.ru:443/payment/start.wsm?%s';

    // test
    const TEST_INIT_URI = 'https://localhost/gaika/payment/test/init?%s';
    const BANK_INIT_URI_PARAMS = 'lang=%s&merch_id=%s&back_url_s=%s&back_url_f=%s&o.trxId=%s';

    // consts
    const CONST_MERCH_ID = '8A7E1A09CAE002CA5D89765EB50A9501';
    const CONST_ACCOUNT_ID = 'C411541C54DF9B9491E4743F851427F0';
    const CONST_LANG_CODE = 'RU';
    const CONST_CURRENCY = 643;

    // check
    const BANK_MERCH_ID = 'merch_id';
    const BANK_TRX_ID = 'trx_id';
    const BANK_LANG_CODE = 'lang_code';
    const BANK_O_TRX_ID = 'o_trxId';
    const BANK_TS = 'ts';
    // registr
    const BANK_MERCHANT_TRX = 'merchant_trx';
    const BANK_RESULT_CODE = 'result_code';
    const BANK_AMOUNT = 'amount';
    const BANK_ACCOUNT_ID = 'account_id';
    const BANK_P_RRN = 'p_rrn';
    const BANK_P_AUTHCODE = 'p_authcode';
    const BANK_P_TRX_DATE = 'p_transmissionDateTime';
    const BANK_P_MASKED_PAN = 'p_maskedPan';
    const BANK_P_CARDHOLDER = 'p_cardholder';
    const BANK_SIGNATURE = 'signature';

    // responses xml
    const XML_RESULT_DESC = 'resultDesc';
    const XML_SHORT_DESC = 'shortDesc';
    const XML_LONG_DESC = 'longDesc';
    const XML_AMOUNT = 'amount';

    // errors
    const ERROR_IN_ORDER = 'errorInOrder';
    const ERROR_STATUS_TRANSACTION = 'errorStatusTransaction';
    const ERROR_IN_CHECK_TRX_ID = 'errorCheckTrxIdRegister';
    const ERROR_IN_CHECK_MERCH_ID = 'errorCheckMerchIdRegister';
    const ERROR_IN_CHECK_ACCOUNT_ID = 'errorCheckAccountIdRegister';
    const ERROR_UNKNOWN_STATUS = 'errorUnknownResultCode';
    const ERROR_SIGNATURE = 'errorDifferentSignature';
    const ERROR_AMOUNT = 'errorDifferentAmount';
    const ERROR_ALREADY_REGISTERED = 'errorAlreadyRegistered';
    const ERROR_UNKNOWN_TYPE = 'errorUnknownType';
    const FATAL_ERROR_ROLLBACK = 'fatalErrorRollback';
    const ERROR_REPEAT_TRX = 'errorInRepeatTrx';

    // data
    const DATA_USER_ID = 'userId';
    const DATA_ORDER_ID = 'orderId';
    const DATA_DELIVERY_ID = 'deliveryId';
    const DATA_AMOUNT = 'summary';

    // status
    const STATUS_INIT = 'init';
    const STATUS_CHECK = 'check';
    const STATUS_CHECK_ERROR = 'errorCheck';
    const STATUS_REGISTER = 'register';
    const STATUS_REGISTER_ERROR = 'errorRegister';
    const STATUS_CANCEL = 'cancel';

    // type
    const TYPE_BALANCE = 'balance';
    const TYPE_ORDER = 'order';
    const TYPE_DELIVERY = 'delivery';
    
    // history
    const HISTORY_ADD = 'add';
    const HISTORY_DEC = 'dec';
    const HISTORY_HOLDADD = 'hold-add';
    const HISTORY_HOLDDEC = 'hold-dec';

    // dbTable
    const DB_ID = 'id';
    const DB_STATUS = 'status';
    const DB_TYPE = 'type';
    const DB_USER_ID = 'userId';
    const DB_ORDER_ID = 'orderId';
    const DB_DELIVERY_ID = 'deliveryId';
    const DB_AMOUNT = 'amount';
    const DB_DESCRIPTION = 'description';
    const DB_BANK_AMOUNT = 'bankAmount';
    const DB_BANK_TRX_ID = 'bankTrxId';
    const DB_BANK_LANG_CODE = 'bankLangCode';
    const DB_BANK_RRN = 'bankRrn';
    const DB_BANK_AUTH_CODE = 'bankAuthCode';
    const DB_BANK_MASKED_PAN = 'bankMaskedPan';
    const DB_BANK_CARDHOLDER = 'bankCardholder';
    const DB_BANK_TRX_DATE = 'bankTrxDate';
    const DB_BANK_TS = 'bankTs';
    const DB_TS = 'ts';

    // order
    const ORDER_USER_HOLD = 'userHold';
    const ORDER_SUMMARY = 'summary';
    // delivery
    const DELIVERY_SUMMARY = 'summary';

    // signature
    const SIGNATURE_DELIMITER = '&signature=';
    const SIGNATURE_QUERY_DATA = 0;
    const SIGNATURE_QUERY_SIGN = 1;
    const SIGNATURE_DIRECTORY = 'files/signature/';
    const SIGNATURE_FILENAME_DATA = 'data';
    const SIGNATURE_FILENAME_SIGN = 'signature';
    const SIGNATURE_FILETYPE_SHA1 = '.sha1';
    const SIGNATURE_FILETYPE_BASE64 = '.base64';
    const SIGNATURE_FILETYPE_DATA = '.data';
    const SIGNATURE_BANK_CER = 'int.www.pps.gazprombank.ru.cer';
    
    // vars
    protected $_logger;
    protected $_dbTable;
    protected $_userModel;
    protected $_orderModel;
    protected $_transaction;
    protected $_user;
    protected $_data;
    protected $_request;
    protected $_response;
    protected $_error;

    public function __construct() {
        $this->_dbTable = new Payment_Model_DbTable_Transaction();
        $this->_userModel = new User_Model_Users();
        $this->_orderModel = new Shop_Model_Orders();
        $this->_logger = Zend_Registry::get( 'logger' );
    }

// ============================
// ========== PUBLIC ==========
// ============================
    public function initTransaction( $amount, $userId, $description, $urlS,
            $urlF, $orderId = null ) {

        if ( isset( $amount ) &&
                isset( $userId ) &&
                isset( $description ) ) {
            $this->_data = array(
                self::DATA_USER_ID => $userId,
                self::DATA_AMOUNT => $amount
            );
        } else {
            throw new Exception( self::ERROR_IN_ORDER );
        }

        if ( !empty( $orderId ) ) {
            $this->_data[ self::DATA_ORDER_ID ] = $orderId;
        }

        $trxId = $this->_initDbRow( $description );

        $url = $this->_initUrlBuilder( $trxId, $urlS, $urlF );

        $this->_transaction[ self::DB_ID ] = $trxId;

        $this->_logReport( self::STATUS_INIT );

        return $url;
    }

    public function check( $params ) {
        try {
            $this->_request = $params;
            $this->_transaction = $this->_dbTable->trxGet( $params[ self::BANK_O_TRX_ID ] );

            if ( $this->_transaction[ self::DB_STATUS ] == self::STATUS_INIT ||
                    $this->_transaction[ self::DB_STATUS ] == self::STATUS_CHECK_ERROR ) {
                $this->_updateDbRowAfterCheck();
            } else {
                throw new Exception( self::ERROR_STATUS_TRANSACTION );
            }

            $this->_buildResponse( self::STATUS_CHECK );

            $this->_logReport( self::STATUS_CHECK );

            return $this->_response;
        } catch ( Exception $e ) {
            $this->_error = $e->getMessage();

            $this->_buildResponse( self::STATUS_CHECK_ERROR );

            $this->_logReport( self::STATUS_CHECK_ERROR, $e->getMessage() );

            return $this->_response;
        }
    }

    public function register( $params ) {
        try {
            // prepare
            $this->_logger->log( 'start register', Zend_Log::INFO );
            $this->_request = $params;
            
            // save in Transaction
            $this->_dbTable->startTrasnaction();
            $this->_transaction = $this->_dbTable->trxGet( $params[ self::BANK_O_TRX_ID ] );

            if ( $this->_transaction[ self::DB_STATUS ] != self::STATUS_CANCEL ) {
                
                if ( $this->_transaction[ self::DB_STATUS ] == self::STATUS_CHECK ||
                        $this->_transaction[ self::DB_STATUS ] == self::STATUS_REGISTER_ERROR ) {
                    $this->_updateDbRowAfterRegister();
                } else {
                    throw new Exception( self::ERROR_STATUS_TRANSACTION );
                }
                
                // data manipulation
                if ( intval( $this->_request[ self::BANK_RESULT_CODE ] ) == 1 ) {

                    $this->_logger->log( 'register - bank result code = 1', Zend_Log::INFO );
                    
                    // check signature
                    $this->_checkSignature();
                    
                    // check amounts
                    if ( intval( floatval( $this->_transaction[ self::DB_AMOUNT ] ) * 100 ) != intval( $params[ self::BANK_AMOUNT ] ) ) {
                        throw new Exception( self::ERROR_AMOUNT );
                    }

                    // choose do
                    if ( $this->_transaction[ self::DB_TYPE ] == self::TYPE_BALANCE ) {
                        $this->_doBalance();
                    } else if ( $this->_transaction[ self::DB_TYPE ] == self::TYPE_ORDER ) {
                        $this->_doOrder();
                    } else if ( $this->_transaction[ self::DB_TYPE ] == self::TYPE_DELIVERY ) {
                        $this->_doDelivery();
                    } else {
                        throw new Exception( self::ERROR_UNKNOWN_TYPE );
                    }
                    
                    
                    $this->_logger->log( 'register - build response', Zend_Log::INFO );

                    $this->_buildResponse( self::STATUS_REGISTER );
                    $this->_logReport( self::STATUS_REGISTER );
                }

                if ( intval( $this->_request[ self::BANK_RESULT_CODE ] ) == 2 ) {

                    if ( $params[ self::BANK_AMOUNT ] != null &&
                            intval( $params[ self::BANK_AMOUNT ] ) == intval( floatval( $this->_transaction[ self::DB_AMOUNT ] ) * 100 )
                    ) {
                        $this->rollbackPayment( $this->_transaction[ self::DB_ID ] );
                    }

                    $this->_buildResponse( self::STATUS_CANCEL );
                    $this->_logReport( self::STATUS_CANCEL );
                }

            } else {
                $this->_buildResponse( self::STATUS_CANCEL );
                $this->_logReport( self::STATUS_CANCEL );
            }
                
            $this->_dbTable->commitTrasnaction();

            return $this->_response;
        } catch ( Exception $e ) {
            $this->_dbTable->rollbackTrasnaction();

            $this->_error = $e->getMessage();

            $this->_buildResponse( self::STATUS_REGISTER_ERROR );

            $this->_logReport( self::STATUS_REGISTER_ERROR,
                    $e->getMessage() . $e->getTraceAsString() );

            return $this->_response;
        }
    }

    public function rollbackPayment( $trxId ) {
        try {
            $this->_transaction = $this->_dbTable->trxGet( $trxId );

            // choose rollback
            if ( $this->_transaction[ self::DB_TYPE ] == self::TYPE_ORDER ) {
                $this->_rollbackOrder();
            } elseif ( $this->_transaction[ self::DB_TYPE ] == self::TYPE_DELIVERY ) {
                $this->_rollbackDelivery();
            }
        } catch ( Exception $e ) {
            throw new Exception( self::FATAL_ERROR_ROLLBACK );
        }
    }
    
    public function createNewTrxForOrder( $orderId, $urlS, $urlF ) {
        try {
            $this->_dbTable->startTrasnaction();
            $this->_transaction = $this->_dbTable->trxGetByOrderIdToRepeat( $orderId );

            // save data and close Trx
            $amount = $this->_transaction[self::DB_AMOUNT];
            $userId = $this->_transaction[self::DB_USER_ID];
            $desc = $this->_transaction[self::DB_DESCRIPTION];

            $this->_updateStatus( self::STATUS_CANCEL );

            $this->_logReport( self::STATUS_CANCEL, 'trx Closed' );
            // create new Trx
            $this->_data = array(
                self::DATA_USER_ID => $userId,
                self::DATA_AMOUNT => $amount,
                self::DATA_ORDER_ID => $orderId
            );
            
            $trxId = $this->_initDbRow( $desc );
            
            $url = $this->_initUrlBuilder( $trxId, $urlS, $urlF );
            
            $this->_transaction = array();
            $this->_transaction[ self::DB_ID ] = $trxId;
            
            $this->_logReport( self::STATUS_INIT, 'trx Repeat' );
            
            $this->_dbTable->commitTrasnaction();
        } catch (Exception $e) {
            $this->_dbTable->rollbackTrasnaction();
            
            throw new Exception( self::ERROR_REPEAT_TRX );
        }
        
        return $url;
    }

// ============================
// ========= PROTECTED ========
// ============================
    ///////////////// INIT /////////////////
    // push in Transaction
    protected function _initDbRow( $description ) {
        $insert = array(
            self::DB_STATUS => self::STATUS_INIT,
            self::DB_USER_ID => $this->_data[ self::DATA_USER_ID ],
            self::DB_AMOUNT => $this->_data[ self::DATA_AMOUNT ],
            self::DB_DESCRIPTION => $description,
            self::DB_TYPE => self::TYPE_BALANCE,
            self::DB_ORDER_ID => null,
            self::DB_DELIVERY_ID => null,
        );
        if ( isset( $this->_data[ self::DATA_ORDER_ID ] ) ) {
            $insert[ self::DB_ORDER_ID ] = $this->_data[ self::DATA_ORDER_ID ];
            $insert[ self::DB_TYPE ] = self::TYPE_ORDER;
        }
        if ( isset( $this->_data[ self::DATA_DELIVERY_ID ] ) ) {
            $insert[ self::DB_DELIVERY_ID ] = $this->_data[ self::DATA_DELIVERY_ID ];
            $insert[ self::DB_TYPE ] = self::TYPE_DELIVERY;
        }
        return $this->_dbTable->trxInsert( $insert );
    }

    // build init url
    protected function _initUrlBuilder( $trxId, $urlS, $urlF ) {
        $url = getenv( 'APPLICATION_ENV' ) == 'development' ?
                self::TEST_INIT_URI : self::BANK_INIT_URI;
        $urlParams = sprintf( self::BANK_INIT_URI_PARAMS, self::CONST_LANG_CODE,
                self::CONST_MERCH_ID, $urlS . '/trx/' . $trxId,
                $urlF . '/trx/' . $trxId, $trxId );
        return sprintf( $url, $urlParams );
    }

    ///////////////// CHECK /////////////////
    // push in Transaction
    protected function _updateDbRowAfterCheck() {
        $update = array(
            self::DB_STATUS => self::STATUS_CHECK,
            self::DB_BANK_TRX_ID => $this->_request[ self::BANK_TRX_ID ],
            self::DB_BANK_LANG_CODE => $this->_request[ self::BANK_LANG_CODE ],
            self::DB_BANK_TS => $this->_request[ self::BANK_TS ]
        );
        $this->_dbTable->trxUpdate( $update, $this->_transaction[ self::DB_ID ] );
    }

    ///////////////// REGISTER /////////////////
    // push in Transaction
    protected function _updateDbRowAfterRegister() {
        $update = array( );

        // checks
        if ( $this->_transaction[ self::DB_BANK_TRX_ID ] != $this->_request[ self::BANK_TRX_ID ] ) {
            throw new Exception( self::ERROR_IN_CHECK_TRX_ID );
        }
        if ( self::CONST_MERCH_ID != $this->_request[ self::BANK_MERCH_ID ] ) {
            throw new Exception( self::ERROR_IN_CHECK_MERCH_ID );
        }

        // check result code and update status
        if ( intval( $this->_request[ self::BANK_RESULT_CODE ] ) == 1 ) {
            $update[ self::DB_STATUS ] = self::STATUS_REGISTER;
        } else if ( intval( $this->_request[ self::BANK_RESULT_CODE ] ) == 2 ) {
            $update[ self::DB_STATUS ] = self::STATUS_CANCEL;
        } else {
            throw new Exception( self::ERROR_UNKNOWN_STATUS );
        }

        // update trx db
        if ( $update[ self::DB_STATUS ] != self::STATUS_CANCEL ) {

            if ( self::CONST_ACCOUNT_ID != $this->_request[ self::BANK_ACCOUNT_ID ] ) {
                throw new Exception( self::ERROR_IN_CHECK_ACCOUNT_ID );
            }

            $update[ self::DB_BANK_AMOUNT ] = floatval( $this->_request[ self::BANK_AMOUNT ] / 100 );
            $update[ self::DB_BANK_TS ] = $this->_request[ self::BANK_TS ];

            if ( isset( $this->_request[ self::BANK_P_AUTHCODE ] ) ) {
                $update[ self::DB_BANK_AUTH_CODE ] = $this->_request[ self::BANK_P_AUTHCODE ];
            }
            if ( isset( $this->_request[ self::BANK_P_RRN ] ) ) {
                $update[ self::DB_BANK_RRN ] = floatval( $this->_request[ self::BANK_P_RRN ] );
            }
            if ( isset( $this->_request[ self::BANK_P_TRX_DATE ] ) ) {
                $update[ self::DB_BANK_TRX_DATE ] = floatval( $this->_request[ self::BANK_P_TRX_DATE ] );
            }
            if ( isset( $this->_request[ self::BANK_P_MASKED_PAN ] ) ) {
                $update[ self::DB_BANK_MASKED_PAN ] = $this->_request[ self::BANK_P_MASKED_PAN ];
            }
            if ( isset( $this->_request[ self::BANK_P_CARDHOLDER ] ) ) {
                $update[ self::DB_BANK_CARDHOLDER ] = $this->_request[ self::BANK_P_CARDHOLDER ];
            }
        }

        $this->_dbTable->trxUpdate( $update, $this->_transaction[ self::DB_ID ] );
    }

    protected function _doBalance() {
        // update balance
        $this->_userModel->updateUpBalance(
                $this->_transaction[self::DB_AMOUNT],
                $this->_transaction[self::DB_USER_ID]
        );

        // update TrxHistory
        $trxHistoryModel = new Payment_Model_TrxHistory();
        $trxHistoryModel->addRowTrx(self::HISTORY_ADD, $this->_transaction);
    }

    protected function _doOrder() {
        // get order
        $order = $this->_orderModel->getOrderByOrderId(
                $this->_transaction[self::DB_ORDER_ID]
        );
        // update balance
        $this->_userModel->updateUpHoldBalance(
                $this->_transaction[self::DB_AMOUNT],
                $this->_transaction[self::DB_USER_ID]
        );

        // update order
        $this->_orderModel->updateStatusFromPayment(
                $this->_transaction[self::DB_ORDER_ID]
        );
        $this->_orderModel->setHold(
                $order[self::ORDER_USER_HOLD] + $order[self::ORDER_SUMMARY],
                $this->_transaction[self::DB_ORDER_ID]
        );
        $this->_orderModel->doOrderAllGoodsMoneyHold(
                $this->_transaction[self::DB_ORDER_ID]
        );

        // update TrxHistory
        $trxHistoryModel = new Payment_Model_TrxHistory();
        $trxHistoryModel->addRowTrx(self::HISTORY_HOLDADD, $this->_transaction);
    }

    protected function _doDelivery() {
        // init models
        $trxHistoryModel = new Payment_Model_TrxHistory();
        $deliveryModel = new Shop_Model_Delivery();

        // update delivery status
        $deliveryModel->updateStatusFromPayment( $this->_transaction[ self::DB_DELIVERY_ID ] );

        // update TrxHistory
        $trxHistoryModel->addRowTrx(self::HISTORY_ADD, $this->_transaction );
        $trxHistoryModel->addRowTrx(self::HISTORY_DEC, $this->_transaction );
    }

    protected function _rollbackOrder() {
        // get order
        $order = $this->_orderModel->getOrderByOrderId( $this->_transaction[ self::DB_ORDER_ID ] );

        // payment manipalution
        $hold = $order[ self::ORDER_USER_HOLD ];
        if ( $hold ) {
            $this->_userModel->updateDownHoldBalance( $hold,
                    $this->_transaction[ self::DB_USER_ID ] );
            $this->_userModel->updateUpBalance( $hold,
                    $this->_transaction[ self::DB_USER_ID ] );
            
            $trxHistoryModel->addRowTrx(self::HISTORY_ADD, $this->_transaction );
        }

        // update status
        $this->_orderModel->updateStatusFromPaymentRollback( $this->_transaction[ self::DB_ORDER_ID ] );
    }

    protected function _rollbackDelivery() {
        // init models
        $deliveryModel = new Shop_Model_Delivery();
        // update status
        $deliveryModel->updateStatusFromPaymentRollback( $this->_transaction[ self::DB_DELIVERY_ID ] );
    }

    ///////////////// RESPONSE /////////////////
    // create $this->_response
    protected function _buildResponse( $status ) {
        switch ( $status ) {
            case self::STATUS_CHECK:
                $this->_response = array(
                    self::XML_LONG_DESC => $this->_transaction[ self::DB_DESCRIPTION ],
                    self::XML_AMOUNT => $this->_transaction[ self::DB_AMOUNT ]
                );
                break;
            
            case self::STATUS_REGISTER:
            case self::STATUS_CANCEL:
                $this->_response = null;
                break;
            
            case self::STATUS_CHECK_ERROR:
            case self::STATUS_REGISTER_ERROR:
                $this->_response = array(
                    self::XML_RESULT_DESC => $this->_error
                );
                $this->_updateStatus( $status );
                break;
        }
    }

    public function checkSignature() {
        $this->_checkSignature();
    }
    
    /////////////////////////////////////////////
    ///////////////// SIGNATURE /////////////////
    protected function _checkSignature() {
        $query = $this->_getFullUrl();
        $queryExploded = explode(self::SIGNATURE_DELIMITER, $query);
        $queryData = $queryExploded[self::SIGNATURE_QUERY_DATA];
        $querySignature = urldecode($queryExploded[self::SIGNATURE_QUERY_SIGN]);
        
        // prepare
        $filePostfix = sha1($querySignature);
        // step 1
        $filesDir = self::SIGNATURE_DIRECTORY;
        $filenameData = self::SIGNATURE_FILENAME_DATA . $filePostfix;
        $filenameDataSha1 = self::SIGNATURE_FILENAME_DATA . $filePostfix . self::SIGNATURE_FILETYPE_SHA1;
        // step 2
        $filenameSignatureBase64 = self::SIGNATURE_FILENAME_SIGN . $filePostfix . self::SIGNATURE_FILETYPE_BASE64;
        $filenameSignatureData = self::SIGNATURE_FILENAME_SIGN . $filePostfix . self::SIGNATURE_FILETYPE_DATA;
        // step 3
        $filenameGpbCer = self::SIGNATURE_BANK_CER;
        
        $files = array(
            $filesDir . $filenameData, $filesDir . $filenameDataSha1,
            $filesDir . $filenameSignatureData,
            $filesDir . $filenameSignatureBase64
        );
        
        $handle = fopen($filesDir . $filenameData, "w+");
        fwrite($handle, $queryData);
        fclose($handle);
        
//        file_put_contents($filesDir . $filenameData, $queryData);
        file_put_contents($filesDir . $filenameSignatureBase64, $querySignature);
        
        // exec
        $ret = exec("openssl dgst -binary -sha1 -out {$filesDir}{$filenameDataSha1} {$filesDir}{$filenameData}", $out, $err);
        if ($err) {
            $this->_deleteFiles($files);
            throw new Exception(self::ERROR_SIGNATURE);
        }
        
        $ret = exec("openssl base64 -in {$filesDir}{$filenameSignatureBase64} -out {$filesDir}{$filenameSignatureData} -d", $out, $err);
        if ($err) {
            $this->_deleteFiles($files);
            throw new Exception(self::ERROR_SIGNATURE);
        }
        
        $command = "openssl pkeyutl -verify -in {$filesDir}{$filenameDataSha1} -sigfile {$filesDir}{$filenameSignatureData} -certin -inkey {$filesDir}{$filenameGpbCer} -pkeyopt digest:sha1";
        $ret = exec($command, $out, $err);
        // logs
        $this->_logger->log('$ret: ' . $ret, Zend_Log::NOTICE);
        $this->_logger->log('$out: ' . $out, Zend_Log::NOTICE);
        $this->_logger->log('$err: ' . $err, Zend_Log::NOTICE);
        
        if ($ret != 'Signature Verified Successfully') {
            $this->_deleteFiles($files);
            throw new Exception(self::ERROR_SIGNATURE);
        }
        
        $this->_deleteFiles($files);
        
//        $this->_logger->log($command, Zend_Log::NOTICE);
    }
    
    private function _deleteFiles($files) {
        foreach ($files as $filename) {
            unlink($filename);
        }
    }
    
    private function _getFullUrl() {
        return 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    ///////////////// ERROR /////////////////
    // push in Transaction
    protected function _updateStatus( $status ) {
        $update = array( self::DB_STATUS => $status );
        $this->_dbTable->trxUpdate( $update, $this->_transaction[ self::DB_ID ] );
    }

    // log writer
    protected function _logReport( $type, $message = null ) {
        $logType = Zend_Log::INFO;
        if ( $message ) {
            $logType = Zend_Log::ERR;
        }
        $firstString = 'Payment.model.<Trx#' . $this->_transaction[ 'id' ] . '>: ';

        $this->_logger->log( $firstString . 'type=' . $type, $logType );
        if ( $message ) {
            $this->_logger->log( $firstString . 'message=' . $message, $logType );
        }
        if ( isset( $this->_request ) ) {
            $this->_logger->log( $firstString . 'requestKeys=' . implode( '|;|',
                            array_keys( $this->_request ) ), $logType );
            $this->_logger->log( $firstString . 'requestValues=' . implode( '|;|',
                            $this->_request ), $logType );
        }
        if ( isset( $this->_response ) ) {
            $this->_logger->log( $firstString . 'responseKeys=' . implode( '|;|',
                            array_keys( $this->_response ) ), $logType );
            $this->_logger->log( $firstString . 'responseValues=' . implode( '|;|',
                            $this->_response ), $logType );
        }
    }

}