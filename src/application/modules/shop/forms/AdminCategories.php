<?php

class Shop_Form_AdminCategories extends ItRocks_Form {

    protected $_category        = array();
    protected $_allCategories   = array();
    protected $_parentId        = '';
    protected $_categoryId      = '';

    public function __construct(Zend_Db_Table_Row_Abstract $category, $allCategories = array(), $parentId = '', $categoryId = '') {
        $this->_category        = $category;
        $this->_allCategories   = $allCategories;
        $this->_parentId        = $parentId;
        $this->_categoryId        = $categoryId;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $selectDecorator    = new ItRocks_Form_Decorator_AdminSelect;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $aliasValidator     = new ItRocks_Validate_Alias();

        $this->addElement($this->createElement('text', 'title', array(
            'required'      => true,
            'label'         => 'adminTitle',
            'value'         => $this->_category->title,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'alias', array(
            'required'      => false,
            'label'         => 'adminAlias',
            'value'         => $this->_category->alias,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'aliasPlaceholder',
            'validators'    => array($aliasValidator),
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('select', 'parentId', array(
            'required'      => false,
            'label'         => 'adminParent',
            'value'         => $this->_parentId,
            'multiOptions'  => $this->_allCategories,
            'readonly'      => false,
            'class'         => 'span12',
            'decorators'    => array($selectDecorator)
        )));


        $this->addElement($this->createElement('text', 'description', array(
            'required'      => false,
            'label'         => 'adminDescription',
            'value'         => $this->_category->description,
            'decorators'    => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required'      => false,
            'label'         => 'metaTitle',
            'value'         => $this->_category->metaTitle,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'meta title',
            'data-size'     => '60',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaKeywords', array(
            'required'      => false,
            'label'         => 'metaKeywords',
            'value'         => $this->_category->metaKeywords,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'meta title',
            'data-size'     => '60',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaDescription', array(
            'required'      => false,
            'label'         => 'metaDescription',
            'value'         => $this->_category->metaDescription,
            'class'         => 'span12',
            'data-size'     => '160',
            'decorators'    => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'save',
            'decorators'    => array($buttonDecorator)
        )));

    }

    public function isValid($category){
        $ret = parent::isValid($category);

        if ($this->_category->alias != $category['alias'])
        {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'Shop_Categories',
                    'field' => 'alias'
                )
            );
            if (!$aliasValidator->isValid($category['alias']))
            {
                $this->alias->addError("Запись с ЧПУ ".$category['alias']. " существует. Придумайте другой вариант или переименуйте существующую запись.");
                $ret = false;
            }
        }

        return $ret;
    }
}