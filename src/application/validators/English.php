<?php

class Validator_English extends Zend_Validate_Regex {
        
    public function __construct() {
//        parent::__construct(false);
    }
    
    public function isValid($value) {
        $translate = Zend_Registry::get('Root_Translate');
        $this->setPattern('/[a-zA-Z]/iU');
        $this->setMessage($translate->_('validateEnglish'));
        
         if(parent::isValid($value)) {
            return true;
        } else return false;
    }
}