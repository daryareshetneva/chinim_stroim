<?php

class Faq_Form_Add extends Zend_Form {
    
    protected $_faq = null;
    
    public function __construct(Zend_Db_Table_Row_Abstract $faq) {
        $this->_faq = $faq;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        
        $this->addElement($this->createElement('text', 'question', array(
            'required' => true,
            'label' => 'question',
            'value' => $this->_faq->question
        )));
        
        $this->addElement($this->createElement('textarea', 'answer', array(
            'required' => true,
            'label' => 'answer',
            'id' => 'faqAnswer',
            'value' => $this->_faq->answer
        )));

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => ($this->_faq->question) ? 'edit' : 'add',
            'type' => 'submit',
        )));
    }
}