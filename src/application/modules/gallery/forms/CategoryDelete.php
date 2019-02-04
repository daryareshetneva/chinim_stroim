<?php

class Gallery_Form_CategoryDelete extends Zend_Form {

    protected $_category;

    public function __construct( $category ) {
	$this->_category = $category;
	parent::__construct();
    }

    public function init() {
	$this->setDecorators( array( 'FormElements', 'Form' ) );
	$this->addAttribs( array( 'class' => 'form-horizontal' ) );

	$translator = Zend_Registry::get( 'Root_Translate' );

	$selectDecorator = new ItRocks_Form_Decorator_AdminSelect();
	$buttonDecorator = new ItRocks_Form_Decorator_AdminButtonForGroup();
	$groupDecorator = new ItRocks_Form_Decorator_AdminButtonGroup();

	$this->addElement( $this->createElement( 'select', db_Photo_Relations::_CATEGORY_ID, array(
		    'required' => true,
		    'label' => 'category',
		    'class' => 'span12',
		    'multiOptions' => $this->_getPairs( $translator->_( "emptyCategory" ) ),
		    'decorators' => array( $selectDecorator )
	) ) );

	$this->addElement( $this->createElement( 'submit', 'remove', array(
		    'label' => 'delete',
		    'class' => 'btn-warning',
		    'type' => 'submit',
		    'decorators' => array( $buttonDecorator )
	) ) );

	$this->addElement( $this->createElement( 'submit', 'move', array(
		    'label' => 'move',
		    'class' => 'btn-success',
		    'type' => 'submit',
		    'decorators' => array( $buttonDecorator )
	) ) );

	$this->addElement( $this->createElement( 'submit', 'cancel', array(
		    'label' => 'cancel',
		    'class' => 'btn-danger',
		    'type' => 'submit',
		    'decorators' => array( $buttonDecorator )
	) ) );

	$this->addDisplayGroup(
		array( 'move', 'remove', 'cancel' ), 'buttonWrap'
	);

	$this->getDisplayGroup( 'buttonWrap' )->addDecorator( $groupDecorator );

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

    public function isValid( $data ) {
	$valid = parent::isValid( $data );

	if ( $valid && $data[ db_Photo_Relations::_CATEGORY_ID ] != 0 || isset( $data[ 'cancel' ] ) || isset( $data[ 'remove' ] ) ) {
	    return true;
	}

	$translator = Zend_Registry::get( 'Root_Translate' );
	$this->getElement( db_Photo_Relations::_CATEGORY_ID )->setErrors( array( $translator->_( "notEmptyCategory" ) ) );

	return false;
    }

    protected function _getPairs( $emptyParent ) {
	$categoryModel = new Gallery_Model_Category();
	$result[ 0 ] = $emptyParent;
	$pairs = $categoryModel->getPairs( $this->_category[ db_PhotoCategory::_ID ] );
	if ( $pairs ) {
	    foreach ( $pairs as $key => $value ) {
		$result[ $key ] = $value;
	    }
	}
	return $result;
    }

}