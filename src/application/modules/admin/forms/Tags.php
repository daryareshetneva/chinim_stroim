<?php

class Admin_Form_Tags extends Zend_Form {
    
    protected $_page = null;
    protected $_aliases = null;
    
    public function __construct($page, $aliases) {
        $this->_page = $page;
        $this->_aliases = $aliases;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        
        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $textAreaDecorator = new ItRocks_Form_Decorator_AdminTextarea();
        $submitButtonDecorator = new ItRocks_Form_Decorator_AdminSubmit;

        
        $this->addElement($this->createElement('text', 'title', array(
            'label' => 'adminPageTitle',
            'value' => $this->_page['title'],
            'readonly' => false,
            'class' => 'span12',
            'placeholder' => '',
            'type' => 'text',
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'metaTitle', array(
            'label' => 'adminPageMetaTitle',
            'value' => $this->_page['metaTitle'],
            'readonly' => false,
            'class' => 'span12',
            'placeholder' => '',
            'type' => 'text',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaDescription', array(
            'label' => 'adminPageMetaDescription',
            'value' => $this->_page['metaDescription'],
            'readonly' => false,
            'class' => 'span12',
            'placeholder' => '',
            'decorators' => array($textAreaDecorator)
        )));
        
        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'editButton',
            'decorators' => array($submitButtonDecorator)
        )));
        
    }
    
}
