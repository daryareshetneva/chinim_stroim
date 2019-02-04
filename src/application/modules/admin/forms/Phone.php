<?php

class Admin_Form_Phone extends ItRocks_Form  {

    protected $_item = null;

    public function __construct($item) {
        $this->_item = $item;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $decoratorSelect = new ItRocks_Form_Decorator_AdminSelect();


        $this->addElement($this->createElement('text', 'phone', array(
            'required' => false,
            'label' => 'phonesTitle',
            'value' => $this->_item->phone,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => '8-3822-52-52-52',
            'decorators' => array($textDecorator)
        )));

        $this->addElement( $this->createElement( 'select', 'isMain', array(
            'required' => true,
            'label' => 'phonesIsMain',
            'multiOptions' => [
                0 => 'Нет',
                1 => 'Да'
            ],
            'class' => 'span12',
            'value' => $this->_item->isMain,
            'decorators' => array( $decoratorSelect )
        ) ) );


        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'save',
            'decorators' => array($buttonDecorator)
        )));
    }
}
