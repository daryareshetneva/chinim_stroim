<?php

class Validator_Identical extends Zend_Validate_Identical {

    protected $_messase;

    public function __construct( $compare, $message ) {
	$this->_message = $message;
	parent::__construct( $compare );
    }

    public function isValid( $value, $context = null ) {

	$this->setMessage( $this->_message );

	if ( parent::isValid( $value ) ) {
	    return true;
	} else {
	    return false;
	}
    }

}
