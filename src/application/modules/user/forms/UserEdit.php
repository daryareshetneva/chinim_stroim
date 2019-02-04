<?php

class User_Form_UserEdit extends Zend_Form {

    protected $_user = null;
    
    public function __construct( $user ) {
        $this->_user = $user;
        parent::__construct();
    }
    
    protected $_mainDecorator = array(
        'ViewHelper',
        array( 'label', array( 'tag' => 'span' ) ),
        array( 'Description', array( 'class' => 'description' ) ),
        array( array( 'row' => 'HtmlTag' ), array( 'tag' => 'div', 'class' => 'element' ) ),
        array( 'Errors', array( 'class' => 'error' ) )
    );

    public function init() {
        $this->setDecorators( array( 'FormElements', 'Form' ) );
	$this->addAttribs( array( 'class' => 'form-horizontal' ) );
        $translator = Zend_Registry::get( 'Root_Translate' );
        
        $textHtml5Decorator = new ItRocks_Form_Decorator_HTML5Text();
        $selectHtml5Decorator = new ItRocks_Form_Decorator_HTML5Select();
        $submitHtml5Decorator = new ItRocks_Form_Decorator_HTML5Submit();
        
        $notEmptyValidator = new Validator_NotEmpty();
        $phoneValidator = new Validator_Phone();
        $alphaSpacesValidator = new Validator_AlphaSpaces();
        $passIdenticalValidator = new Validator_Identical(
                Zend_Controller_Front::getInstance()->getRequest()->getPost( 'password' ),
                $translator->_( 'validatePasswordConfirm' ) );
        
        $this->addElement( $this->createElement( 'text', 'fio', array(
                    'isRequired' => true,
                    'required' => true,
                    'type' => 'text',
                    'label' => 'editFio',
                    'value' => $this->_user['fio'],
                    'decorators' => array( $textHtml5Decorator ),
                    'validators' => array( 
                        $alphaSpacesValidator, $notEmptyValidator )
        ) ) );
        
        $this->addElement( $this->createElement( 'password', 'password', array(
                    'isRequired' => false,
                    'required' => false,
                    'type' => 'password',
                    'label' => 'editNewPassword',
                    'decorators' => array( $textHtml5Decorator )
        ) ) );

        $this->addElement( $this->createElement( 'password', 'confirm', array(
                    'isRequired' => false,
                    'required' => false,
                    'type' => 'password',
                    'label' => 'editNewConfirm',
                    'decorators' => array( $textHtml5Decorator ),
                    'validators' => array(
                        $passIdenticalValidator )
        ) ) );

        $this->addElement( $this->createElement( 'text', 'phone', array(
                    'isRequired' => true,
                    'required' => true,
                    'type' => 'tel',
                    'label' => 'editPhone',
                    'value' => $this->_user['phone'],
                    'decorators' => array( $textHtml5Decorator ),
                    'validators' => array( $phoneValidator, $notEmptyValidator )
        ) ) );

//        
//        $this->addElement($this->createElement('select', 'status', array(
//                    'isRequired' => true,
//                    'required' => true,
//                    'label' => 'editStatus',
//                    'class' => 'span12',
//                    'value' => $this->_user['status'],
//                    'multiOptions' => array('normal' => 'regNormalUser', 'business' => 'regBusinessUser'),
//                    'decorators' => array( $selectHtml5Decorator )
//        ) ) );

        $this->addElement( $this->createElement( 'submit', 'submit', array(
                    'label' => 'editSave',
                    'class' => 'button',
                    'decorators' => array( $submitHtml5Decorator )
        ) ) );

        foreach ( $this->getElements() as $element ) {
            $element->removeDecorator( 'DtDdWrapper' );
        }
    }

}