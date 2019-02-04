<?php

class Portfolio_Form_Portfolio extends Zend_Form {

    protected $_portfolio = null;

    public function __construct(Zend_Db_Table_Row_Abstract $portfolio) {
        $this->_portfolio = $portfolio;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');

        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $selectDecorator = new ItRocks_Form_Decorator_AdminSelect;
        $uploadDecorator = new ItRocks_Form_Decorator_AdminUpload;
        $buttonDecorator = new ItRocks_Form_Decorator_AdminSubmit;
        $dateDecorator      = new ItRocks_Form_Decorator_AdminDate;

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'portfolioTitle',
            'value' => $this->_portfolio->title,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'portfolioTitlePlaceholder',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'alias', array(
            'required' => false,
            'label' => 'portfolioAlias',
            'value' => $this->_portfolio->alias,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'portfolioAliasPlaceholder',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => false,
            'label' => 'portfolioMetaTitle',
            'value' => $this->_portfolio->metaTitle,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'Input meta title...',
            'data-size' => '60',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaDescription', array(
            'required' => false,
            'label' => 'portfolioMetaDescription',
            'value' => $this->_portfolio->metaDescription,
            'class' => 'span8',
            'data-size' => '160',
            'decorators' => array($textareadDecorator)
        )));

        $portfolioCatalogsTable = new Portfolio_Model_DbTable_PortfolioCatalogs();

        $this->addElement($this->createElement('select', 'portfolioCatalogId', array(
            'required' => true,
            'label' => 'portfolioCatalog',
            'value' => $this->_portfolio->portfolioCatalogId,
            'multiOptions' => $portfolioCatalogsTable->getPairs(),
            'decorators' => array($selectDecorator)
        )));

        $this->addElement($this->createElement('text', 'description', array(
            'required' => true,
            'label' => 'portfolioDescription',
            'value' => $this->_portfolio->description,
            'class' => 'span8',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'miniDescription', array(
            'required' => true,
            'label' => 'miniDescription',
            'value' => $this->_portfolio->miniDescription,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'portfolioTitlePlaceholder',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'price', array(
            'required' => true,
            'label' => 'projectPrice',
            'value' => $this->_portfolio->price,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => '100',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'date', array(
            'required' => true,
            'label' => 'projectDate',
            'id' => 'projectDate',
            'value' => (!empty($this->_portfolio->date)) ? date('m/d/Y', strtotime($this->_portfolio->date)) : date('m/d/Y'),
            'class' => 'span2',
            'decorators' => array($dateDecorator),
        )));

        $this->addElement($this->createElement('submit', 'submit', array(
            'label' => ($this->_portfolio->title) ? 'portfolioEditButton' : 'portfolioAddButton',
            'decorators' => array($buttonDecorator)
        )));
    }

    public function isValid($data) {
        $ret = parent::isValid($data);

        if (!empty($_FILES['image']['name'][0])) {
            for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                $filename = $_FILES['image']['name'][$i];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!in_array($ext, array('jpg', 'jpeg', 'png'))) {
                    throw new Exception('wrongFileExtension');
                    return false;
                }
            }
        }

        return $ret;
    }
}
