<?php

class User_Form_UserCar extends Zend_Form {

    protected $_car = null;

    public function __construct( $car = null ) {
        $this->_car = $car;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators( array( 'FormElements', 'Form' ) );
        $this->addAttribs( array( 'class' => 'form-car-edit form-inline' ) );
        $this->addAttribs( array( 'role' => 'form' ) );

        $textDecorator = new ItRocks_Form_Decorator_FrontInlineText();
        $submitButtonDecorator = new ItRocks_Form_Decorator_FrontInlineSubmit();
        $groupRowDecorator = new ItRocks_Form_Decorator_RowGroup();
        $groupColDecorator = new ItRocks_Form_Decorator_ColGroup();

        $digitValidator = new Validator_Digit();
        $notEmptyValidator = new Validator_NotEmpty();
        
        $this->addElement($this->createElement('text', db_UserCars::_VEHICLE, array(
            'isRequired' => true,
            'required' => true,
            'placeholder' => 'userAddCarVehicle',
            'type' => 'text',
            'value' => ($this->_car == null) ? '' : $this->_car[ db_UserCars::_VEHICLE ],
            'decorators' => array($textDecorator),
            'validators' => array($notEmptyValidator)
        )));
        
        $this->addElement($this->createElement('text', db_UserCars::_MODEL, array(
            'isRequired' => true,
            'required' => true,
            'placeholder' => 'userAddCarModel',
            'type' => 'text',
            'value' => ($this->_car == null) ? '' : $this->_car[ db_UserCars::_MODEL ],
            'decorators' => array($textDecorator),
            'validators' => array($notEmptyValidator)
        )));
        
        $this->addElement($this->createElement('text', db_UserCars::_YEAR, array(
            'isRequired' => true,
            'required' => true,
            'placeholder' => 'userAddCarYear',
            'type' => 'number',
            'value' => ($this->_car == null) ? null : $this->_car[ db_UserCars::_YEAR ],
            'decorators' => array($textDecorator),
            'validators' => array($digitValidator)
        )));
        
        $this->addElement($this->createElement('text', db_UserCars::_VIN, array(
            'isRequired' => true,
            'required' => true,
            'placeholder' => 'userAddCarVIN',
            'type' => 'text',
            'value' => ($this->_car == null) ? '' : $this->_car[ db_UserCars::_VIN ],
            'decorators' => array($textDecorator),
            'validators' => array($notEmptyValidator)
        )));
	
        $this->addElement($this->createElement('submit', 'submit', array(
            'label' => 'userAddCarSubmit',
            'decorators' => array($submitButtonDecorator)
        )));
        
        $this->addDisplayGroup(
            array( db_UserCars::_VEHICLE,  db_UserCars::_MODEL, db_UserCars::_YEAR, db_UserCars::_VIN ),
            'firstRow'
        );
        $this->addDisplayGroup(
            array( 'submit' ),
            'secondRow'
        );
	
        $this->getDisplayGroup('firstRow')->addDecorator($groupColDecorator);
        $this->getDisplayGroup('firstRow')->addDecorator($groupRowDecorator);
        $this->getDisplayGroup('secondRow')->addDecorator($groupRowDecorator);
	
	foreach ( $this->getElements() as $element ) {
            $element->removeDecorator( 'DtDdWrapper' );
	    $element->removeDecorator( 'Fieldset' );
	    $element->removeDecorator( 'HtmlTag' );
        }
	foreach ( $this->getDisplayGroups() as $element ) {
            $element->removeDecorator( 'DtDdWrapper' );
	    $element->removeDecorator( 'Fieldset' );
	    $element->removeDecorator( 'HtmlTag' );
        }
    }
}