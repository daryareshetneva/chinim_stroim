<?php

class Validator_RecordExists extends Zend_Validate_Db_RecordExists
{
    public function  __construct($table, $field, $message)
    {
        $this->_message = $message;
        parent::__construct($table, $field);
    }
    
    public function isValid($value)
    {
        $this->setMessage($this->_message);
        
        if (!empty($value)) {
            if (parent::isValid($value)) {
                return true;
            } else {
                $this->_error();
            }
        }
    }
}