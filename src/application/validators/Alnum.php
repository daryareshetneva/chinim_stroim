<?php

class Validator_Alnum extends Zend_Validate_Alnum {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function isValid($value) {
        $translate = Zend_Registry::get('Root_Translate');
        $this->setMessage($translate->_('validateAlnum'));
        
         if(parent::isValid($value)) {
            return true;
        } else return false;
    }
}