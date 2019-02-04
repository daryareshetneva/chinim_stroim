<?php

class User_Form_DecBalance extends Zend_Form {

    protected $_user = null;
    
    public function __construct( $user ) {
        $this->_user = $user;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        
        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $submitButtonDecorator = new ItRocks_Form_Decorator_AdminSubmit;
        $digitValidator = new Validator_Money(null);
        
        $this->addElement($this->createElement('text', 'email', array(
            'label' => 'userEmail',
            'placeholder' => 'userEmail',
            'class' => 'input-large',
            'readonly' => true,
            'value' => $this->_user['email'],
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'fio', array(
            'label' => 'userFio',
            'placeholder' => 'userFio',
            'class' => 'input-xlarge',
            'readonly' => true,
            'value' => $this->_user['fio'],
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'balance', array(
            'label' => 'userBalance',
            'class' => 'input-xlarge',
            'readonly' => true,
            'value' => $this->_user[ db_Users::_BALANCE ],
            'decorators' => array($textDecorator)
        )));
	
        $this->addElement($this->createElement('text', 'decBalance', array(
            'required' => true,
            'label' => 'userDecBalance',
            'placeholder' => 'userDecBalancePh',
            'class' => 'input-xlarge',
            'decorators' => array($textDecorator),
            'validators' => array($digitValidator)
        )));
        
        
        $this->addElement($this->createElement('submit', 'submit', array(
            'label' => 'userDecBalanceSubmit',
            'decorators' => array($submitButtonDecorator)
        )));
        
    }
}