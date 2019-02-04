<?php

class Validator_Length extends Zend_Validate_Abstract {
    
    protected  $min;
    protected  $max;
    
    const ERROR = '';
    protected $_messageTemplates = array();
    
    public function __construct($min, $max) {
        $this->max = $max;
        $this->min = $min;
        $translate = Zend_Registry::get('Root_Translate');
        $message = $translate->_('validateLength');
        $this->_messageTemplates[self::ERROR] = sprintf($message, $min, $max);
    }
    
    public function isValid($value) {
        if (((mb_strlen($value, 'UTF-8') ) < $this->min) || ((mb_strlen($value, 'UTF-8')) > $this->max)) {
            $this->_error(self::ERROR);
            return false;
        } else {
            return true;
        } 
    }
      
}
