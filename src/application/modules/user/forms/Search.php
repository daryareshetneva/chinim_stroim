<?php

class User_Form_Search extends Zend_Form 
{
    protected $_query = null;

    public function __construct($query = null) {
        $this->_query = $query;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('method', 'GET');
        $decorator = new ItRocks_Form_Decorator_AdminSearch();
        $translate = Zend_Registry::get('Zend_Translate');
        
        $this->addElement($this->createElement('text', 'query', array(
            'value' => ($this->_query) ? $this->_query : $translate->_('search'),
            'decorators' => array($decorator)
        )));
        
        foreach ($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper');
        }
    }
    
}