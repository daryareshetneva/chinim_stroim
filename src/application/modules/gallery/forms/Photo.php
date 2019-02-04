<?php

class Gallery_Form_Photo extends Zend_Form {

    protected $_photo = null;
    protected $_categoryId = null;

    public function __construct( $photo = null, $categoryId = null ) {
	$this->_photo = $photo;
	$this->_categoryId = $categoryId;
	parent::__construct();
    }

    public function init() {
	$this->setDecorators( array( 'FormElements', 'Form' ) );
	$this->addAttribs( array( 'class' => 'form-horizontal' ) );

	$validatorNotEmpty = new Validator_NotEmpty();

	$decoratorText = new ItRocks_Form_Decorator_AdminText();
	$decoratorTextarea = new ItRocks_Form_Decorator_AdminTextarea();
	$decoratorSelect = new ItRocks_Form_Decorator_AdminSelect();
	$decoratorSubmit = new ItRocks_Form_Decorator_AdminSubmit();
        $decoratorUpload = new ItRocks_Form_Decorator_AdminUpload();

	$this->addElement( $this->createElement( 'text', db_Photos::_TITLE, array(
		    'required' => true,
		    'label' => 'title',
		    'value' => $this->_getTitle(),
		    'class' => 'span12',
		    'validators' => array( $validatorNotEmpty ),
		    'decorators' => array( $decoratorText )
	) ) );

	$this->addElement( $this->createElement( 'textarea', db_Photos::_DESC, array(
		    'label' => 'description',
		    'id' => 'description',
		    'class' => 'span12',
		    'rows' => '5',
		    'value' => $this->_getDesc(),
		    'decorators' => array( $decoratorTextarea )
	) ) );

	$pairs = $this->_getPairs();

	$this->addElement( $this->createElement( 'select', db_Photo_Relations::_CATEGORY_ID, array(
		    'required' => true,
		    'label' => 'formPhotoCategoryId',
		    'multiOptions' => $pairs,
		    'class' => 'span12',
		    'value' => $this->_getCategoryId(),
		    'validators' => array( $validatorNotEmpty ),
		    'decorators' => array( $decoratorSelect )
	) ) );


	$this->addElement( $this->createElement( 'file', 'image', array(
		    'required' => ( $this->_isEmpty() ) ? true : false,
		    'label' => 'formAddPhotoLabel',
		    'decorators' => array( $decoratorUpload )
	) ) );

	if ( !$this->_isEmpty() ) {
	    $this->image->addDecorator( new ItRocks_Form_Decorator_AdminImageView( array(
		'imageUrl' => $this->_getImage(),
		'imageTitle' => 'currentImage'
	    ) ) );
	}

	$this->addElement( $this->createElement( 'button', 'submit', array(
		    'label' => ( $this->_isEmpty() ) ? 'add' : 'edit',
		    'type' => 'submit',
		    'decorators' => array( $decoratorSubmit )
	) ) );
    }

    protected function _getPairs() {
	$categoryModel = new Gallery_Model_Category();
	$pairs = $categoryModel->getPairs();
	return $pairs;
    }
    
    protected function _getCategoryId() {
	return empty( $this->_categoryId ) ? 0 : $this->_categoryId;
    }    

    protected function _isEmpty() {
	return empty( $this->_photo );
    }

    protected function _getId() {
	return $this->_getFiled( db_Photos::_ID ) == "" ?
		null :
		$this->_getFiled( db_Photos::_ID );
    }

    protected function _getTitle() {
	return $this->_getFiled( db_Photos::_TITLE );
    }
    
    protected function _getImage() {
	return $this->_getFiled( db_Photos::_SRC );
    }

    protected function _getDesc() {
	return $this->_getFiled( db_Photos::_DESC );
    }

    protected function _getFiled( $field ) {
	if ( $this->_isEmpty() ) {
	    return "";
	}
	if ( isset( $this->_photo[ $field ] ) ) {
	    return $this->_photo[ $field ];
	}
	return "";
    }

}
