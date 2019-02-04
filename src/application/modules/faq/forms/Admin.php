<?php

class Faq_Form_Admin extends Zend_Form {
    
    protected $_faq = null;

    public function __construct(Zend_Db_Table_Row_Abstract $faq) {
        $this->_faq = $faq;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        
        $textareaDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $checkboxDecorator = new ItRocks_Form_Decorator_AdminCheckbox;
        $submitDecorator = new ItRocks_Form_Decorator_AdminSubmit;
        
        $this->addElement($this->createElement('textarea', 'answer', array(
            'required' => true,
            'label' => 'answer',
            'value' => $this->_faq->answer,
            'id' => 'faqsAnswer',
            'decorators' => array($textareaDecorator)
        )));
        
        $this->addElement($this->createElement('checkbox', 'show', array(
            'label' => 'show',
            'checked' => $this->_faq->show,
            'styled' => 'styled',
            'decorators' => array($checkboxDecorator)
        )));
        
        $this->addElement($this->createElement('checkbox', 'reply', array(
            'label' => 'sendReply',
            'styled' => 'styled',
            'decorators' => array($checkboxDecorator)
        )));

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => 'reply',
            'type' => 'submit',
            'decorators' => array($submitDecorator)
        )));
    }
}