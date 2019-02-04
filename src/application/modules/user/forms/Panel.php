<?php

class User_Form_Panel extends Zend_Form {
    
    protected $_mainDecorator = array(
        'viewHelper',
        array('Label', array('tag' => 'span')),
        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),
        array('Errors', array('class' => 'error'))
    );
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setDefaultTranslator(Zend_registry::get('Root_Translate'));
        
        $this->addElement($this->createElement('text', 'login', array(
            'required' => true,
            'label' => 'login',
            'validators' => array(new Validator_NotEmpty()),
            'decorators' => $this->_mainDecorator
        )));

        $this->addElement($this->createElement('password', 'password', array(
            'required' => true,
            'label' => 'pass',
            'validators' => array(new Validator_NotEmpty()),
            'decorators' => $this->_mainDecorator
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'enter',
        )));
        
        foreach ($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper');
        }
    }
}