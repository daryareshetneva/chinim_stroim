<?php

class User_Form_UserQuery extends Zend_Form {

    protected $_pairs = null;
    protected $_carId = null;

    public function __construct( $pairs = null, $carId = null ) {
        $this->_pairs = $pairs;
        $this->_carId = $carId;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators( array( 'FormElements', 'Form' ) );
        $this->addAttribs( array( 'class' => 'form-horizontal' ) );

        $textareaDecorator = new ItRocks_Form_Decorator_HTML5Textarea();
        $selectDecorator = new ItRocks_Form_Decorator_HTML5Select();
        $submitButtonDecorator = new ItRocks_Form_Decorator_HTML5Submit();
        
        
        $this->addElement($this->createElement('select', 'car', array(
            'required' => true,
            'label' => 'userQueryCar',
            'multiOptions' => $this->_pairs,
            'value' => $this->_carId != null ? $this->_carId : '',
            'decorators' => array($selectDecorator)
        )));
        
        $this->addElement($this->createElement('textarea', 'question', array(
            'isRequired' => true,
            'required' => true,
            'label' => 'userQueryDescription',
            'decorators' => array($textareaDecorator)
        )));
        
        
        $this->addElement($this->createElement('submit', 'submit', array(
            'label' => 'userQuerySubmit',
            'decorators' => array($submitButtonDecorator)
        )));
        
        foreach ( $this->getElements() as $element ) {
            $element->removeDecorator( 'DtDdWrapper' );
        }
    }
}