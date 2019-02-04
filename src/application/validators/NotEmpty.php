<?php

class Validator_NotEmpty extends Zend_Validate_NotEmpty {
    
    public function isValid($value) {
        $translate = Zend_Registry::get('Root_Translate');
        $this->setMessage($translate->_('validateNotEmpty'));
        
         if(parent::isValid($value)) {
            return true;
        } else return false;
    }
}
