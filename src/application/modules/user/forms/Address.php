<?php

class User_Form_Address extends Zend_Form {

    protected $_address = null;

    public function __construct($address) {
        $this->_address = $address;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators( array( 'FormElements', 'Form' ) );
        $this->addAttribs( array( 'class' => 'form-horizontal' ) );
        $translator = Zend_Registry::get( 'Root_Translate' );

        $textHtml5Decorator = new ItRocks_Form_Decorator_HTML5Text();
        $selectHtml5Decorator = new ItRocks_Form_Decorator_HTML5Select();
        $submitHtml5Decorator = new ItRocks_Form_Decorator_HTML5Submit();

        $citiesTable = new User_Model_DbTable_Cities;        
        if (!$this->_address->cityId) {
            $defaultCityId = $citiesTable->getIdByCity('Томск');
        }

        $this->addElement($this->createElement('select', 'cityId', array(
            'required' => true,
            'label' => 'userAddressCity',
            'class' => 'span12',
            'value' => (!$this->_address->cityId) ? $defaultCityId : $this->_address->cityId,
            'multiOptions' => $citiesTable->getPairs(),
            'decorators' => array($selectHtml5Decorator)
        )));

        $this->addElement($this->createElement('text', 'index', array(
            'required' => true,
            'label' => 'userAddressIndex',
            'type' => 'text',
            'value' => $this->_address->index,
            'decorators' => array($textHtml5Decorator)
        )));
        
        $this->addElement($this->createElement('text', 'address', array(
            'required' => true,
            'label' => 'userAddressField',
            'type' => 'text',
            'value' => $this->_address->address,
            'decorators' => array($textHtml5Decorator)
        )));

        $this->addElement( $this->createElement('submit', 'submit', array(
            'label' => 'editSave',
            'class' => 'button',
            'decorators' => array( $submitHtml5Decorator )
        ) ) );
    }
}