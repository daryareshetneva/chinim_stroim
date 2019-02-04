<?php

class Shop_Form_AdminProduct extends ItRocks_Form {

    protected $_product        = array();
    protected $_allCategories   = array();

    public function __construct(Zend_Db_Table_Row_Abstract $product, $allCategories) {
        $this->_product        = $product;
        $this->_allCategories  = $allCategories;
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
        $adminFileDecorator = new ItRocks_Form_Decorator_AdminFile();
        $aliasValidator     = new ItRocks_Validate_Alias();
        $discountValidator  = new Zend_Validate_Between(['min' => 0, 'max' => 100]);

        $this->addElement($this->createElement('text', 'title', array(
            'required'      => true,
            'label'         => 'adminTitle',
            'value'         => $this->_product->title,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'description', array(
            'required'      => false,
            'label'         => 'adminDescription',
            'value'         => $this->_product->description,
            'decorators'    => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('file', 'mainImage', array(
            'required' => ($this->_product->image) ? false : true,
            'label' => 'adminMainImage',
            'decorators' => array($adminFileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Model_Images_Product();
        $this->mainImage->addFilter($imageHelper);
        if ($this->_product->image) {
            $this->mainImage->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_product->image),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('text', 'price', array(
            'required'      => true,
            'label'         => 'adminPrice',
            'value'         => $this->_product->price,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'discount', array(
            'required'      => false,
            'label'         => 'adminDiscount',
            'value'         => $this->_product->discount,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator),
            'validators'    => [$discountValidator]
        )));

        $this->addElement($this->createElement('text', 'alias', array(
            'required'      => false,
            'label'         => 'adminAlias',
            'value'         => $this->_product->alias,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'aliasPlaceholder',
            'validators'    => array($aliasValidator),
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('select', 'categoryId', array(
            'required'      => true,
            'label'         => 'adminProductCategory',
            'value'         => $this->_product->categoryId,
            'multiOptions'  => $this->_allCategories,
            'readonly'      => false,
            'class'         => 'span12',
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('text', 'count', array(
            'required'      => true,
            'label'         => 'adminCount',
            'value'         => $this->_product->count,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'productCountPlaceholder',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('select', 'deleted', array(
            'required'      => false,
            'label'         => 'adminDeleted',
            'value'         => $this->_product->deleted,
            'multiOptions'  => [
                0   => 'Нет',
                1   => 'Да'
            ],
            'readonly'      => false,
            'class'         => 'span12',
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('select', 'sale', array(
            'required'      => false,
            'label'         => 'adminSale',
            'value'         => $this->_product->sale,
            'multiOptions'  => [
                0   => 'Нет',
                1   => 'Да'
            ],
            'readonly'      => false,
            'class'         => 'span12',
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('select', 'new', array(
            'required'      => false,
            'label'         => 'adminProductNew',
            'value'         => $this->_product->sale,
            'multiOptions'  => [
                0   => 'Нет',
                1   => 'Да'
            ],
            'readonly'      => false,
            'class'         => 'span12',
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('select', 'productOfDay', array(
            'required'      => false,
            'label'         => 'adminProductOfDay',
            'value'         => $this->_product->productOfDay,
            'multiOptions'  => [
                0   => 'Нет',
                1   => 'Да'
            ],
            'readonly'      => false,
            'class'         => 'span12',
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required'      => false,
            'label'         => 'metaTitle',
            'value'         => $this->_product->metaTitle,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'meta title',
            'data-size'     => '60',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaKeywords', array(
            'required'      => false,
            'label'         => 'metaKeywords',
            'value'         => $this->_product->metaKeywords,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => 'meta title',
            'data-size'     => '60',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaDescription', array(
            'required'      => false,
            'label'         => 'metaDescription',
            'value'         => $this->_product->metaDescription,
            'class'         => 'span12',
            'data-size'     => '160',
            'decorators'    => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'save',
            'decorators'    => array($buttonDecorator)
        )));

    }

    public function isValid($product){
        $ret = parent::isValid($product);

        if ($this->_product->alias != $product['alias'])
        {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'Shop_Products',
                    'field' => 'alias'
                )
            );
            if (!$aliasValidator->isValid($product['alias']))
            {
                $this->alias->addError("Запись с ЧПУ ".$product['alias']. " существует. Придумайте другой вариант или переименуйте существующую запись.");
                $ret = false;
            }
        }

        if ($product['categoryId'] == 0)
        {
            $this->categoryId->addError("Выберите категорию товара");
            $ret = false;
        }

        if (!preg_match('/^[0-9\.]+$/', $product['price']))
        {
            $this->price->addError("Цена некорректная. Только цифры и символ точки.");
            $ret = false;
        }

        if (!preg_match('/^[0-9]+$/', $product['count']))
        {
            $this->count->addError("Неправильно задано колличество. Только цифры. Если товара нет в наличии, укажите 0.");
            $ret = false;
        }

        return $ret;
    }
}