<?php

class Portfolio_Form_PortfolioCatalog extends Zend_Form {

    protected $_catalog = null;

    public function __construct(Zend_Db_Table_Row_Abstract $catalog) {
        $this->_catalog = $catalog;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');

        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $buttonDecorator = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'portfolioCatalogTitle',
            'value' => $this->_catalog->title,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'portfolioCatalogTitlePlaceholder',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'alias', array(
            'required' => false,
            'label' => 'portfolioCatalogAlias',
            'value' => $this->_catalog->alias,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'portfolioCatalogAliasPlaceholder',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => false,
            'label' => 'portfolioCatalogMetaTitle',
            'value' => $this->_catalog->metaTitle,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'portfolioCatalogMetaTitlePlaceholder',
            'data-size' => '60',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaDescription', array(
            'required' => false,
            'label' => 'portfolioCatalogMetaDescriptionPlaceholder',
            'value' => $this->_catalog->metaDescription,
            'class' => 'span8',
            'data-size' => '160',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'description', array(
            'required' => true,
            'label' => 'portfolioCatalogDescription',
            'value' => $this->_catalog->description,
            'class' => 'span8',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => ($this->_catalog->title) ? 'portfolioEditButton' : 'portfolioAddButton',
            'decorators' => array($buttonDecorator)
        )));

    }

    public function isValid($data){
        $ret = parent::isValid($data);

        if ($this->_catalog->alias != $data['alias']) {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'PortfolioCatalogs',
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
