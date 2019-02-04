<?php

class Reviews_Form_User extends Zend_Form {
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));

        $textDecorator = new ItRocks_Form_Decorator_Text;
        $textAreaDecorator = new ItRocks_Form_Decorator_TextArea;

        $this->addElement($this->createElement('text', 'userLogin', array(
            'required' => true,
            'label' => 'reviewUsername',
            'class' => 'form-control',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'shortAnswer', array(
            'required' => true,
            'label' => 'reviewMessage',
            'class' => 'form-control',
            'decorators' => array($textAreaDecorator)
        )));        

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => 'buttonReview',
            'class' => 'btn btn-primary',
            'type' => 'submit'
        )));
    }
}