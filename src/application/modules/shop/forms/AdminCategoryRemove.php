<?php

class Shop_Form_AdminCategoryRemove extends ItRocks_Form {

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

        $selectDecorator    = new ItRocks_Form_Decorator_AdminSelect;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('select', 'feature', array(
            'required'      => true,
            'label'         => 'removeCategoryFeature',
            'value'         => false,
            'multiOptions'  => [
                'move' => 'Перенести в другую категорию',
                'remove' => 'Удалить товары'
            ],
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'aliasPlaceholder',
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('select', 'parentId', array(
            'required'      => false,
            'label'         => 'moveСategoryItems',
            'value'         => $this->_parentId,
            'multiOptions'  => $this->_allCategories,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'aliasPlaceholder',
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'save',
            'decorators'    => array($buttonDecorator)
        )));

    }
}