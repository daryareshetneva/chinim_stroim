<?php

class Shop_Form_OrderStatus extends ItRocks_Form {
    protected $_item = [];
    protected $_status = [];

    public function __construct($item, $status = []) {
        $this->_item = $item;
        $this->_status = $status;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $selectDecorator    = new ItRocks_Form_Decorator_AdminSelect;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit();

        $this->addElement($this->createElement('select', 'status', array(
            'required'      => false,
            'label'         => 'Статус',
            'value'         => $this->_item[0]['status'],
            'multiOptions'  => $this->_status,
            'readonly'      => false,
            'class'         => 'span12',
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'save',
            'decorators'    => array($buttonDecorator)
        )));
    }
}