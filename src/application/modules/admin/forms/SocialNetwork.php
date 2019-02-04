<?php

class Admin_Form_SocialNetwork extends ItRocks_Form  {

    protected $_item = null;

    public function __construct($item) {
        $this->_item = $item;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();

        $urlValidator       = new ItRocks_Validate_ExternalUrl();


        $this->addElement($this->createElement('text', 'title', array(
            'required' => false,
            'label' => 'setTitle',
            'value' => $this->_item->title,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'Мы Вконтакте!',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'url', array(
            'required' => true,
            'label' => 'socialNetworksTitle',
            'value' => $this->_item->url,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'Пример: https://vk.com/spleanru',
            'validators' => array($urlValidator),
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('file', 'img', array(
            'required' => ($this->_item->img) ? false : true,
            'label' => 'Иконка',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Model_Images_Common();
        $this->img->addFilter($imageHelper);
        if ($this->_item->img) {
            $this->img->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_item->img),
                'imageAlternate' => ''
            )));
        }


        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'save',
            'decorators' => array($buttonDecorator)
        )));
    }
}
