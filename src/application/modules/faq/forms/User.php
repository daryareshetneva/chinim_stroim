<?php

class Faq_Form_User extends Zend_Form {
    
    protected $_user = null;
    
    public function __construct($user = null) {
        $this->_user = $user;
        parent::__construct();
    }   
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));

        $textDecorator = new ItRocks_Form_Decorator_Text;
        $textAreaDecorator = new ItRocks_Form_Decorator_TextArea;
        $buttonDecorator = new ItRocks_Form_Decorator_Button;
        
        $this->addElement($this->createElement('text', 'fio', array(
            'required' => true,
            'label' => 'faqFio',
            'value' => !empty($this->_user) ? $this->_user->fio : null,
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'email', array(
            'label' => 'faqEmail',
            'value' => !empty( $this->_user ) ? $this->_user->email : null,
            'validators' => array(array('EmailAddress')),
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('textarea', 'question', array(
            'required' => true,
            'label' => 'faq',
            'id' => 'faqsContent',
            'value' => '',
            'decorators' => array($textAreaDecorator)
        )));

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => 'buttonReview',
            'type' => 'submit',
            'decorators' => array($buttonDecorator)
        )));
    }
    
}