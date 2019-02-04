<?php

class Admin_Form_MailSettings extends Zend_Form {
    
    protected $_settings = null;
    
    public function __construct($settings) {
        $this->_settings = $settings;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $passDecorator = new ItRocks_Form_Decorator_AdminPassword;
        $submitButtonDecorator = new ItRocks_Form_Decorator_AdminSubmit;
        $checkboxDecorator = new ItRocks_Form_Decorator_AdminCheckbox;
        
        $this->addElement($this->createElement('text', 'mailHost', array(
            'label' => 'mailHost',
            'value' => $this->_settings['mailHost'],
            'decorators' => array($textDecorator),
            'class' => 'input-xlarge',
            'validators' => array(new Validator_NotEmpty())
        )));
        
        $this->addElement($this->createElement('text', 'mailUsername', array(
            'label' => 'mailUsername',
            'value' => $this->_settings['mailUsername'],
            'decorators' => array($textDecorator),
            'class' => 'input-xlarge',
            'validators' => array(new Validator_NotEmpty())
        )));
        
        $this->addElement($this->createElement('text', 'mailPassword', array(
            'label' => 'mailPassword',
            'value' => $this->_settings['mailPassword'],
            'decorators' => array($passDecorator),
            'class' => 'input-xlarge',
            'validators' => array(new Validator_NotEmpty())
        )));
        
        $this->addElement($this->createElement('checkbox', 'mailSmtp', array(
            'label' => 'mailSmtp',
            'value' => $this->_settings['mailSmtp'],
            'id' => 'mailSmtp',
            'decorators' => array($checkboxDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'edit',
            'decorators' => array($submitButtonDecorator)
        )));
        
    }
}