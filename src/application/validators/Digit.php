<?php

class Validator_Digit extends Zend_Validate_Digits {
    
    public function isValid($value) {
        $translate = Zend_Registry::get('Root_Translate');
        $this->setMessage($translate->_('validateDigit'));
        
         if(parent::isValid($value)) {
            return true;
        } else return false;
    }
}