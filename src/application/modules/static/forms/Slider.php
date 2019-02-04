<?php

class Static_Form_Slider extends ItRocks_Form {

    protected $_item = null;

    public function __construct(Zend_Db_Table_Row_Abstract $item) {
        $this->_item = $item;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();
        $areaDecorator      = new ItRocks_Form_Decorator_AdminTextarea();
        $textDecorator      = new ItRocks_Form_Decorator_AdminText();
        $submitDecorator    = new ItRocks_Form_Decorator_AdminSubmit();

        $this->addElement($this->createElement('text', 'title', array(
            'required' => false,
            'label' => 'Заголовок',
            'class' => 'span12',
            'value' => $this->_item->title,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('file', 'sliderImage', array(
            'required' => ($this->_item->image) ? false : true,
            'label' => 'sliderImage',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Model_Images_Slider();
        $this->sliderImage->addFilter($imageHelper);
        if ($this->_item->image) {
            $this->sliderImage->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_item->image),
                'imageAlternate' => ''
            )));
        }


        $this->addElement($this->createElement('text', 'url', array(
            'required' => false,
            'label' => 'sliderUrl',
            'class' => 'input-xlarge',
            'value' => $this->_item->url,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'add',
            'decorators' => array($submitDecorator)
        )));

    }
}