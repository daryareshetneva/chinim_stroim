<?php

class Payment_TestController extends Zend_Controller_Action {

    const CHECK_URL = 'http://localhost/gaika/payment/index/check';
    const CHECK_URL_PARAMS = '?trx_id=BFA9505E2198A08A6B64B6ACCEC3DEC5&lang_code=RU&merch_id=90F8AB554402BD64A2A0038C60C61124&o.trxId=';

    const REGISTER_URL = 'https://localhost/gaika/payment/index/register?';
    const REGISTER_URL_PARAMS_1 = 'trx_id=BFA9505E2198A08A6B64B6ACCEC3DEC5&merch_id=90F8AB554402BD64A2A0038C60C61124&result_code=1&amount=171800&account_id=9C533706EC6C1D98DD2743D554A2FD38&o.trxId=';
    const REGISTER_URL_PARAMS_2 = '&p.rrn=001000275177&p.transmissionDateTime=0924184006&ts=20100924+18%3A40%3A37';
    const REGISTER_URL_PARAMS_3 = '&signature=MraRIZUGUpUgmoCypzi2OAoQSEL5k3XF2vTehffNzznNptgxBNQIFvqnVGR6ldMRe%2B3CgcWXhXDH%0Ahv4M%2Bn47371E6jI%2FO7E9vSCCv9iCj6HTU6amPOxTbwZDBQd7JlUyGues9MDbFuGmym6xpyNTSNJy%0Au88FETPeJ5yxEErQveA%3D';

    public function initAction() {
        if ($this->_checkAppEnv()) {
            $this->_helper->layout()->disableLayout();
            $this->redirect( self::CHECK_URL . self::CHECK_URL_PARAMS . $_GET[ 'o_trxId' ] . '&ts=' .time() );
        }
    }

    public function registerAction() {
        if ($this->_checkAppEnv()) {
            $this->_helper->layout()->disableLayout();
            $this->redirect( self::REGISTER_URL . self::REGISTER_URL_PARAMS_1 .
                    $_GET[ 'o_trxId' ] . self::REGISTER_URL_PARAMS_2 .
                    self::REGISTER_URL_PARAMS_3 );
        }
    }
    
    public function checkAuthAction() {
        if ($this->_checkAppEnv()) {
            $this->_helper->layout()->disableLayout();
            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, self::CHECK_URL);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, "gpbPaymentShell:VmboOkOsbfS");
            
            $response = curl_exec($curl);
            if(curl_errno($curl)){ 
                Zend_Debug::dump("error: " . curl_error($curl));
            }
            Zend_Debug::dump($response);
            curl_close($curl);
        } else {
            throw new Exception("ERROR");
        }
    }
    
    private function _checkAppEnv() {
        if (APPLICATION_ENV == 'development') {
            return true;
        }
        return false;
    }

}