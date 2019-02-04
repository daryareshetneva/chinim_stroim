<?php

class HotelRooms_Form_Request extends Zend_Form {


    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('id', 'booking-form');

        $textDecorator      = new ItRocks_Form_Decorator_HotelRoomsText();
        $submitDecorator    = new ItRocks_Form_Decorator_HotelRoomsSubmit();

        $this->addElement($this->createElement('text', 'fio', array(
            'required' => true,
            'label' => 'ФИО',
            'class' => '',
            'id' => 'booking-fio',
            'value' => '',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'phone', array(
            'required' => true,
            'label' => 'Телефон',
            'class' => '',
            'id' => 'booking-phone',
            'value' => '',
            'decorators' => array($textDecorator)
        )));
        $this->addElement($this->createElement('hidden', 'room', array(
            'class' => '',
            'id' => 'booking-room',
            'value' => ''
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'Забронировать',
            'decorators' => array($submitDecorator)
        )));
    }

}