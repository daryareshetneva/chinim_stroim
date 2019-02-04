<?php

class Validator_Max extends Zend_Validate_Abstract {
    
    protected  $max;
    
    const ERROR = '';
    protected $_messageTemplates = array();
    
    public function __construct($max) {
        $this->max = $max;
        $translate = Zend_Registry::get('Root_Translate');
        $message = $translate->_('validateMax');
        $this->_messageTemplates[self::ERROR] = sprintf($message,$max);
    }
    
    public function isValid($value) {
        if ((mb_strlen($value, 'UTF-8')) > $this->max) {
            $this->_error(self::ERROR);
            return false;
        } else {
            return true;
        } 
    }
      
}
