<?php

class HotelRooms_Form_RoomPhoto extends Zend_Form {

    protected $_roomPhoto = null;

    public function __construct(Zend_Db_Table_Row_Abstract $roomPhoto) {
        $this->_roomPhoto = $roomPhoto;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs( array( 'class' => 'form-horizontal' ) );

        $submitDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();


        $this->addElement($this->createElement('file', 'photo', array(
            'required' => (!empty($this->_roomPhoto->photo)) ? false : true,
            'label' => 'Фотография',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new HotelRooms_Model_Images();
        $this->photo->addFilter($imageHelper);
        if ($this->_roomPhoto->photo) {
            $this->photo->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_roomPhoto->photo),
                'imageAlternate' => ''
            )));
        }


        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => (!empty($this->_roomPhoto->photo)) ? 'Редактировать' : 'Добавить',
            'decorators' => array($submitDecorator)
        )));
    }

}