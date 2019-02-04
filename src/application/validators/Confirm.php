<?php

class Validator_Confirm extends Zend_Validate_GreaterThan {
    
    public function __construct() {
        parent::__construct(false, array(0));
    }
    
    public function isValid($value) {
        $translate = Zend_Registry::get('Root_Translate');
        $this->setMessage($translate->_('validateConfirm'));
        
         if(parent::isValid($value)) {
            return true;
        } else return false;
    }
}