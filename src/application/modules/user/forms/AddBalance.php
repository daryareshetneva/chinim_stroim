<?php

class User_Form_AddBalance extends Zend_Form {

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
            'value' => $this->_user[ db_Users::_EMAIL ],
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'fio', array(
            'label' => 'userFio',
            'placeholder' => 'userFio',
            'class' => 'input-xlarge',
            'readonly' => true,
            'value' => $this->_user[ db_Users::_FIO ],
            'decorators' => array($textDecorator)
        )));
	
        $this->addElement($this->createElement('text', 'balance', array(
            'label' => 'userBalance',
            'class' => 'input-xlarge',
            'readonly' => true,
            'value' => $this->_user[ db_Users::_BALANCE ],
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'addBalance', array(
            'required' => true,
            'label' => 'userAddBalance',
            'placeholder' => 'userAddBalancePh',
            'class' => 'input-xlarge',
            'decorators' => array($textDecorator),
            'validators' => array($digitValidator)
        )));
        
        
        $this->addElement($this->createElement('submit', 'submit', array(
            'label' => 'userAddBalanceSubmit',
            'decorators' => array($submitButtonDecorator)
        )));
        
    }
}