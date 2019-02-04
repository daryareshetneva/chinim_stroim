<?php

class Blog_Form_Blog extends ItRocks_Form  {

    protected $_diy = null;
    protected $_tags = '';
    protected $_date = '';

    public function __construct(Zend_Db_Table_Row_Abstract $diy, $tags = '', $date = '') {
        $this->_diy     = $diy;
        $this->_tags    = $tags;
        $this->_date    = $date;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $dateDecorator      = new ItRocks_Form_Decorator_AdminDate;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();
        $aliasValidator     = new ItRocks_Validate_Alias();


        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'blogTitle',
            'value' => $this->_diy->title,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'How to make a...',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'date', array(
            'required' => true,
            'label' => 'blogDate',
            'id' => 'diyDate',
            'value' => $this->_date,
            'class' => 'span8',
            'decorators' => array($dateDecorator)
        )));

        $this->addElement($this->createElement('file', 'image', array(
            'required' => false,
            'label' => 'blogImage',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Blog_Model_Images();
        $this->image->addFilter($imageHelper);
        if ($this->_diy->image) {
            $this->image->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_diy->image),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('text', 'shortDescription', array(
            'required' => true,
            'label' => 'blogShortDescription',
            'value' => $this->_diy->shortDescription,
            'class' => 'span8',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'description', array(
            'required' => true,
            'label' => 'blogDescription',
            'value' => $this->_diy->description,
            'class' => 'span8',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'alias', array(
            'required' => false,
            'label' => 'blogAliasTitle',
            'value' => $this->_diy->alias,
            'readonly' => false,
            'class' => 'span8',
            'validators' => array($aliasValidator),
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => false,
            'label' => 'blogMetaTitle',
            'value' => $this->_diy->metaTitle,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'meta title',
            'data-size' => '60',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaDescription', array(
            'required' => false,
            'label' => 'blogMetaDescription',
            'value' => $this->_diy->metaDescription,
            'class' => 'span8',
            'data-size' => '160',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaKeywords', array(
            'required' => false,
            'label' => 'blogMetaKeywords',
            'value' => $this->_diy->metaKeywords,
            'readonly' => false,
            'class' => 'span8',
            'data-size' => '160',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => ($this->_diy->title) ? 'blogEditButton' : 'blogAddButton',
            'decorators' => array($buttonDecorator)
        )));
    }

    public function isValid($data){
        $ret = parent::isValid($data);

        if (empty($data['alias'])) {
            $transliterateModel = new Model_Transliterate();
            $data['alias'] = $transliterateModel->transliterate($data['title']);
        }

        if ($this->_diy->alias != $data['alias'] || empty($this->_diy->alias)) {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'Blog',
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
