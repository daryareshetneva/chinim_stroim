<?php

class Gallery_Form_Photos extends Zend_Form {

    protected $_categoryId = null;

    public function __construct( $categoryId = null ) {
	$this->_categoryId = $categoryId;
	parent::__construct();
    }

    public function init() {
	$this->setDecorators( array( 'FormElements', 'Form' ) );
	$this->addAttribs( array( 'class' => 'form-horizontal' ) );

	$validatorNotEmpty = new Validator_NotEmpty();

	$decoratorSelect = new ItRocks_Form_Decorator_AdminSelect();
	$decoratorSubmit = new ItRocks_Form_Decorator_AdminSubmit();

	$translator = Zend_Registry::get( 'Root_Translate' );

	$pairs = $this->_getPairs( $translator->_( "emptyCategory" ) );

	$this->addElement( $this->createElement( 'select', db_Photo_Relations::_CATEGORY_ID, array(
		    'required' => true,
		    'label' => 'formPhotosCategory',
		    'multiOptions' => $pairs,
		    'class' => 'span12',
		    'value' => $this->_categoryId,
		    'validators' => array( $validatorNotEmpty ),
		    'decorators' => array( $decoratorSelect )
	) ) );

	$this->addElement( $this->createElement( 'button', 'submit', array(
		    'label' => 'load',
		    'type' => 'submit',
		    'decorators' => array( $decoratorSubmit )
	) ) );
    }

    public function isValid( $data ) {
	$valid = parent::isValid( $data );

	if ( !empty( $_FILES[ 'image' ][ 'name' ][ 0 ] ) ) {
	    for ( $i = 0; $i < count( $_FILES[ 'image' ][ 'name' ] ); $i++ ) {
		$filename = $_FILES[ 'image' ][ 'name' ][ $i ];
		$ext = pathinfo( $filename, PATHINFO_EXTENSION );
		if ( !in_array( $ext, array( 'jpg', 'jpeg', 'png' ) ) ) {
		    return false;
		}
	    }
	} else {
	    return false;
	}

	if ( $valid && $data[ db_Photo_Relations::_CATEGORY_ID ] == 0 ) {
	    return false;
	    $translator = Zend_Registry::get( 'Root_Translate' );
	    $this->getElement( db_Photo_Relations::_CATEGORY_ID )->setErrors( array( $translator->_( "notEmptyCategory" ) ) );
	}


	return $valid;
    }

    protected function _getPairs( $emptyParent ) {
	$categoryModel = new Gallery_Model_Category();
	$result[ 0 ] = $emptyParent;
	$pairs = $categoryModel->getPairs();

	if ( $pairs ) {
	    foreach ( $pairs as $key => $value ) {
		$result[ $key ] = $value;
	    }
	}
	return $result;
    }

}
