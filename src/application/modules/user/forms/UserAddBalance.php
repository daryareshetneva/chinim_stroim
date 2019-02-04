<?php

class User_Form_UserAddBalance extends Zend_Form {

    public function __construct() {
        parent::__construct();
    }
    
    public function isValid( $data ) {
	$valid = parent::isValid( $data );
	
	if ( $data[ 'amount' ] == 0 ) {
	    return false;
	}
	
	return $valid;
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        
        $textDecorator = new ItRocks_Form_Decorator_HTML5Text();
        $submitButtonDecorator = new ItRocks_Form_Decorator_HTML5Submit();
        $digitValidator = new Validator_Money(null);
                
        $this->addElement($this->createElement('text', 'amount', array(
            'isRequired' => true,
            'required' => true,
            'label' => 'userAddBalanceLabel',
            'placeholder' => 'userAddBalancePlaceHolder',
            'type' => 'text',
            'decorators' => array($textDecorator),
            'validators' => array($digitValidator)
        )));
        
        
        $this->addElement($this->createElement('submit', 'submit', array(
            'label' => 'userAddBalanceSubmit',
            'decorators' => array($submitButtonDecorator)
        )));
        
        foreach ( $this->getElements() as $element ) {
            $element->removeDecorator( 'DtDdWrapper' );
        }
    }
}