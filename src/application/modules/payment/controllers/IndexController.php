<?php

class Payment_IndexController extends Zend_Controller_Action {
    
    const PHP_AUTH_USER = 'PHP_AUTH_USER';
    const PHP_AUTH_PW = 'PHP_AUTH_PW';

    protected $_model;
    protected $_logger;
    protected $_logType = Zend_Log::INFO;
    
    public function __construct( Zend_Controller_Request_Abstract $request,
            Zend_Controller_Response_Abstract $response,
            array $invokeArgs = array( ) ) {
        parent::__construct( $request, $response, $invokeArgs );
        header("Content-type: application/xml");

        $this->_model = new Payment_Model_Payment();
        $this->_logger = Zend_Registry::get( 'logger' );
        
    }

    public function checkAction() {
        if (!$this->_checkAuth()) {
            header('WWW-Authenticate: Basic realm="gaika.su payment"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        
        $this->_logger->log( 'Action.check.start: ----------------------------------',
                $this->_logType );
        $this->_logger->log( 'Action.check: request keys:' . implode( '|;|',
                        array_keys( $this->_request->getParams() ) ), $this->_logType );
        $this->_logger->log( 'Action.check: request values:' . implode( '|;|',
                        $this->_request->getParams() ), $this->_logType );

        $this->_helper->layout()->disableLayout();
        $data = $this->_model->check( $this->_request->getParams() );
        if ( isset( $data[ 'resultDesc' ] ) ) {
            $this->view->assign( 'resultDesc', $data[ 'resultDesc' ] );
        }
        if ( isset( $data ) ) {
            $this->view->assign( 'data', $data );
        }

        $this->_logger->log( 'Action.check: response keys:' . implode( '|;|',
                        array_keys( $data ) ), $this->_logType );
        $this->_logger->log( 'Action.check: response values:' . implode( '|;|',
                        $data ), $this->_logType );
        $this->_logger->log( 'Action.check.end: ----------------------------------',
                $this->_logType );
    }

    public function registerAction() {
        $this->_logger->log( 'Action.register.start: ----------------------------------',
                $this->_logType );
        $this->_logger->log( 'Action.register: request keys:' . implode( '|;|',
                        array_keys( $this->_request->getParams() ) ), $this->_logType );
        $this->_logger->log( 'Action.register: request values:' . implode( '|;|',
                        $this->_request->getParams() ), $this->_logType );
        
        $this->_helper->layout()->disableLayout();
        
        $data = $this->_model->register( $this->_request->getParams() );
        if ( isset( $data[ 'resultDesc' ] ) ) {
            $this->view->assign( 'resultDesc', $data[ 'resultDesc' ] );
        }
        
        if ($data) {
            $this->_logger->log( 'Action.register: response keys:' . implode( '|;|',
                            array_keys( $data ) ), $this->_logType );
            $this->_logger->log( 'Action.register: response values:' . implode( '|;|',
                            $data ), $this->_logType );
        }
        $this->_logger->log( 'Action.register.end: ----------------------------------',
                $this->_logType );
    }
    
    private function _checkAuth() {
        if (
                isset($_SERVER[self::PHP_AUTH_USER])
                && isset($_SERVER[self::PHP_AUTH_PW])
        ) {
            $login = $this->getInvokeArg('bootstrap')->getOption('paymentGPBlogin');
            $pass = $this->getInvokeArg('bootstrap')->getOption('paymentGPBpassword');
            
            if (
                    $login == $_SERVER[self::PHP_AUTH_USER]
                    && $pass == md5($_SERVER[self::PHP_AUTH_PW])
            ) {
                return true;
            }
        }
        return false;
    }
}