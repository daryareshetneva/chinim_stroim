<?php

class Admin_Form_Settings extends ItRocks_Form  {

    protected $_mainSettings = array();
    protected $_contacts = array();
    protected $_discountSettings = array();

    public function __construct(array $mainSettings, $contacts) {
        $this->_mainSettings    = $mainSettings;
        $this->_contacts        = $contacts;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $urlValidator       = new ItRocks_Validate_ExternalUrl();
        $mailValidator      = new Validator_Mail();
        $phoneValidator     = new ItRocks_Validate_Phone();
        $adminFileDecorator = new ItRocks_Form_Decorator_AdminFile();

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'Основной Meta-заголовок сайта',
            'value' => $this->_mainSettings['title'],
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'Веб-студия ItRocks',
            'decorators' => array($textDecorator)
        )));


        $this->addElement($this->createElement('text', 'keywords', array(
            'required' => true,
            'label' => 'setKeywords',
            'value' => $this->_mainSettings['keywords'],
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'разработка сайтов, продвижение сайтов, довольные клиенты',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'description', array(
            'required' => true,
            'label' => 'Meta-описание главной страницы',
            'value' => $this->_mainSettings['description'],
            'class' => 'span8',
            'data-size' => '160',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'workTime', array(
            'required' => false,
            'label' => 'Время работы',
            'value' => $this->_contacts['workTime'],
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'С 9:00 до 18:00',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'phone', array(
            'required' => false,
            'label' => 'Телефон',
            'value' => $this->_contacts['phone'],
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => '8(999)999-99-99',
            'decorators' => array($textDecorator),
            'validators' => array($phoneValidator)
        )));

        $this->addElement($this->createElement('text', 'address', array(
            'required' => false,
            'label' => 'Адрес',
            'value' => $this->_contacts['address'],
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'г.Томск ул. Яковлева 71',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'email', array(
            'required' => true,
            'label' => 'E-mail для приема заявок',
            'value' => $this->_contacts['email'],
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'hi@mydomain.ru',
            'validators' => array($mailValidator),
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'mainSettingsSave',
            'decorators' => array($buttonDecorator)
        )));
    }
}
