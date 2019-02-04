<?php

class Validator_NotInArray extends Zend_Validate_InArray
{
    public function isValid($value) {
        if(!parent::isValid($value)) {
            return true;
        } else  {
            $this->_messages = array('INVALID' => "Alias повторяется");
            return false;
        }
    }
}
