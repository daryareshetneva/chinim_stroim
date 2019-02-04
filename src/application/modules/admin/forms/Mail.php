<?php

class Admin_Form_Mail extends Zend_Form {
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        
        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $textareaDecorator = new ItRocks_Form_Decorator_AdminTextarea;
//        $checkboxDecorator = new ItRocks_Form_Decorator_AdminCheckbox;
        
        $this->addElement($this->createElement('text', 'subject', array(
            'required' => true,
            'label' => 'subject',
            'decorators' => array( $textDecorator )
        )));
        
        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'title',
            'decorators' => array( $textDecorator )
        )));
        
        $this->addElement($this->createElement('textarea', 'body', array(
            'required' => true,
            'class' => 'span12',
            'label' => 'mailBody',
            'id' => 'mailBody',
            'decorators' => array( $textareaDecorator )
        )));

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => 'send',
            'type' => 'submit',
            'decorators' => array( new ItRocks_Form_Decorator_AdminSubmit() )
        )));
    }
}