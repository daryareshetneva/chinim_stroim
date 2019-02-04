<?php

class Shop_Form_AdminFilterElement extends ItRocks_Form {

    protected $_filterElement        = array();

    public function __construct(Zend_Db_Table_Row_Abstract $filterElement) {
        $this->_filterElement = $filterElement;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('text', 'title', array(
            'required'      => true,
            'label'         => 'adminTitle',
            'value'         => $this->_filterElement->title,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));


        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'save',
            'decorators'    => array($buttonDecorator)
        )));

    }

}