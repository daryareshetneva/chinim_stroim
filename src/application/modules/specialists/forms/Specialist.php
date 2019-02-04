<?php

class Specialists_Form_Specialist extends Zend_Form {

    protected $_specialist = null;

    public function __construct(Zend_Db_Table_Row_Abstract $specialist) {
        $this->_specialist = $specialist;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $dateDecorator      = new ItRocks_Form_Decorator_AdminDate;
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('text', 'fio', array(
            'required' => true,
            'label' => 'specialistFio',
            'value' => $this->_specialist->fio,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'Иоган Себастьян Бах',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'post', array(
            'required' => false,
            'label' => 'specialistPost',
            'value' => $this->_specialist->post,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'specialistPost',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('file', 'photo', array(
            'required' => ($this->_specialist->photo) ? false : true,
            'label' => 'specialistPhoto',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Specialists_Model_Images();
        $this->photo->addFilter($imageHelper);
        if ($this->_specialist->photo) {
            $this->photo->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_specialist->photo),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('textarea', 'shortDescription', array(
            'required' => true,
            'label' => 'shortDescription',
            'id' => 'shortDescription',
            'value' => $this->_specialist->shortDescription,
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'description', array(
            'required' => true,
            'label' => 'description',
            'id' => 'description',
            'value' => $this->_specialist->description,
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => false,
            'label' => 'metaTitle',
            'class' => 'span8',
            'value' => $this->_specialist->metaTitle,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaDescription', array(
            'required' => false,
            'label' => 'metaDescription',
            'class' => 'span8',
            'value' => $this->_specialist->metaDescription,
            'data-size' => '160',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaKeywords', array(
            'required' => false,
            'label' => 'metaKeywords',
            'value' => $this->_specialist->metaKeywords,
            'readonly' => false,
            'class' => 'span8',
            'data-size' => '160',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => ($this->_specialist->fio) ? 'edit' : 'add',
            'decorators' => array($buttonDecorator)
        )));
    }
}