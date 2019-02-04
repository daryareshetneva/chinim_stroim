<?php

class Validator_Alpha extends Zend_Validate_Alpha {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function isValid($value) {
        $translate = Zend_Registry::get('Root_Translate');
        $this->setMessage($translate->_('validateAlpha'));
        
         if(parent::isValid($value)) {
            return true;
        } else return false;
    }
}