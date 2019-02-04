<?php

class Gallery_Form_Category extends Zend_Form {

    protected $_category = null;
    protected $_categoryId = null;

    public function __construct( $category = null, $categoryId = null ) {
        $this->_category = $category;
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
        $aliasValidator     = new ItRocks_Validate_Alias();
        $adminFileDecorator = new ItRocks_Form_Decorator_AdminFile();
        $translator = Zend_Registry::get( 'Root_Translate' );

        $this->addElement( $this->createElement( 'text', db_PhotoCategory::_TITLE, array(
                'required' => true,
                'label' => 'formCategoryTitle',
                'value' => $this->_getTitle(),
                'class' => 'span12',
                'validators' => array( $validatorNotEmpty ),
                'decorators' => array( $decoratorText )
        ) ) );

        $this->addElement($this->createElement('file', 'image', array(
            'required' => ($this->_getImage()) ? false : true,
            'label' => 'formImage',
            'decorators' => array($adminFileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Model_Images_GalleryCategory();
        $this->image->addFilter($imageHelper);
        if ($this->_getImage()) {
            $this->image->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_getImage()),
                'imageAlternate' => ''
            )));
        }

        $this->addElement( $this->createElement( 'textarea', db_PhotoCategory::_DESC, array(
                'label' => 'formCategoryDesc',
                'id' => 'description',
                'class' => 'span12',
                'rows' => '5',
                'value' => $this->_getDesc(),
                'decorators' => array( $decoratorTextarea )
        ) ) );

        $pairs = $this->_getPairs( $translator->_( "emptyParent" ) );

        $this->addElement( $this->createElement( 'select', db_PhotoCategory::_PID, array(
                'required' => true,
                'label' => 'formCategoryPid',
                'multiOptions' => $pairs,
                'class' => 'span12',
                'value' => $this->_getPid(),
                'validators' => array( $validatorNotEmpty ),
                'decorators' => array( $decoratorSelect )
        ) ) );

        $this->addElement($this->createElement('text', 'alias', array(
            'required' => false,
            'label' => 'formAliasTitle',
            'value' => $this->_getAlias(),
            'readonly' => false,
            'class' => 'span12',
            'placeholder' => 'formAliasPlaceholder',
            'validators' => array($aliasValidator),
            'decorators' => array($decoratorText)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => false,
            'label' => 'formMetaTitle',
            'value' => $this->_getMetaTitle(),
            'readonly' => false,
            'class' => 'span12',
            'placeholder' => 'meta title',
            'data-size' => '60',
            'decorators' => array($decoratorText)
        )));

        $this->addElement($this->createElement('text', 'metaDesc', array(
            'required' => false,
            'label' => 'formMetaDesc',
            'value' => $this->_getMetaDesc(),
            'class' => 'span12',
            'data-size' => '160',
            'decorators' => array($decoratorTextarea)
        )));

        $this->addElement( $this->createElement( 'button', 'submit', array(
                'label' => ( $this->_isEmpty() ) ? 'add' : 'edit',
                'type' => 'submit',
                'decorators' => array( $decoratorSubmit )
        ) ) );
    }

    protected function _getPairs( $emptyParent ) {
	$categoryModel = new Gallery_Model_Category();
	$result[0] = $emptyParent;
	$pairs = $categoryModel->getPairsForEdit( $this->_getId() );
	if ($pairs) {
	    foreach ( $pairs as $key => $value ) {
		$result[$key] = $value;
	    }
	}
	return $result;
    }

    protected function _isEmpty() {
	return empty( $this->_category );
    }

    protected function _getId() {
	return $this->_getFiled( db_PhotoCategory::_ID ) == "" ?
		null :
		$this->_getFiled( db_PhotoCategory::_ID );
    }
    
    protected function _getPid() {
	if ( $this->_categoryId ) {
	    return $this->_categoryId;
	}
	return $this->_getFiled( db_PhotoCategory::_PID ) == "" ?
		0 :
		$this->_getFiled( db_PhotoCategory::_PID );
    }

    protected function _getTitle() {
        return $this->_getFiled( db_PhotoCategory::_TITLE );
    }

    protected function _getImage() {
        return $this->_getFiled( db_PhotoCategory::_IMAGE);
    }

    protected function _getAlias() {
        return $this->_getFiled( db_PhotoCategory::_ALIAS );
    }

    protected function _getDesc() {
        return $this->_getFiled( db_PhotoCategory::_DESC );
    }

    protected function _getMetaTitle() {
        return $this->_getFiled( db_PhotoCategory::_METATITLE);
    }

    protected function _getMetaDesc() {
        return $this->_getFiled( db_PhotoCategory::_METADESC);
    }

    protected function _getFiled( $field ) {
	if ( $this->_category == null ) {
	    return "";
	}
	if ( isset( $this->_category[ $field ] ) ) {
	    return $this->_category[ $field ];
	}
	return "";
    }

    public function isValid($data){
        $ret = parent::isValid($data);

        if ($this->_getAlias() != $data['alias']) {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'Photo_Categories',
                    'field' => 'alias'
                )
            );
            if (!$aliasValidator->isValid($data['alias'])) {
                $this->alias->addError("Запись с ЧПУ ".$data['alias']. " существует. Придумайте другой вариант или переименуйте существующую запись.");
                $ret = false;
            }
        }

        return $ret;
    }

}
