<?php

class Shop_Form_Import extends Zend_Form {

    protected $_extension = 'xls';

    public function __construct( $extension = null ) {
        if ( $extension ) {
            $this->_extension = $extension;
        }

        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs( array( 'class' => 'form-horizontal' ) );

        $fileDecorator = new ItRocks_Form_Decorator_AdminFile();
        $submitDecorator = new ItRocks_Form_Decorator_AdminSubmit();

        $file = $this->createElement('file', 'file', array(
            'required' => true,
            'label' => 'importFile',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', true, array($this->_extension))
            )
        ));
        $this->addElement($file);

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => 'buttonImport',
            'type' => 'submit',
            'decorators' => array($submitDecorator)
        )));
    }
}