<?php

class Partners_Form_Partner extends Zend_Form {

    protected $_partner = null;

    public function __construct(Zend_Db_Table_Row_Abstract $partner) {
        $this->_partner = $partner;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');

        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $dateDecorator = new ItRocks_Form_Decorator_AdminDate;
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();
        $buttonDecorator = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'partnerTitle',
            'value' => $this->_partner->title,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'Иоган Себастьян Бах',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'url', array(
            'required' => false,
            'label' => 'partnerUrl',
            'value' => $this->_partner->url,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'http://google.com',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('file', 'photo', array(
            'required' => ($this->_partner->photo) ? false : true,
            'label' => 'partnerImage',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Partners_Model_Images();
        $this->photo->addFilter($imageHelper);
        if ($this->_partner->photo) {
            $this->photo->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_partner->photo),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => ($this->_partner->title) ? 'edit' : 'add',
            'decorators' => array($buttonDecorator)
        )));

    }

    public function isValid($data)
    {
        $res = parent::isValid($data);

        if (!empty($data['url'])) {
            if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
                $res = false;
                $this->url->addError('Некорректный формат ссылки');
            }
        }

        return $res;
    }
}