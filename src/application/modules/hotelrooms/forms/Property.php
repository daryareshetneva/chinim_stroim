<?php

class HotelRooms_Form_Property extends Zend_Form {

    protected $_property = null;

    public function __construct(Zend_Db_Table_Row_Abstract $property) {
        $this->_property = $property;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs( array( 'class' => 'form-horizontal' ) );


        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $submitDecorator    = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'Название',
            'class' => 'span8',
            'value' => $this->_property->title,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'value', array(
            'required' => false,
            'label' => 'Значение',
            'class' => 'span8',
            'value' => $this->_property->value,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => (!empty($this->_property->title)) ? 'Редактировать' : 'Добавить',
            'decorators' => array($submitDecorator)
        )));
    }

}