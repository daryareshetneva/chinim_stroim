<?php

class Validator_Mail extends Zend_Validate_EmailAddress {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function isValid($value) {
       $translate = Zend_Registry::get('Root_Translate');
       
       if(parent::isValid($value)) {
            return true;
        } else {
            $this->_messages = array(self::INVALID => $translate->_('validateMail'));
        }
    }
}