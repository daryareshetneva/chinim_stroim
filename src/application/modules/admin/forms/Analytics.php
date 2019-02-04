<?php

class Admin_Form_Analytics extends ItRocks_Form  {

    protected $_counters = [];

    public function __construct(array $counters = ['googleAnalytics' => '' ,'yandexMetrika' => '']) {
        $this->_counters = $counters;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textAreaDecorator  = new ItRocks_Form_Decorator_AdminTextarea;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;


        $this->addElement($this->createElement('text', 'yandexMetrika', array(
            'required'      => false,
            'label'         => 'yandexMetrika',
            'class'         => 'span12',
            'value'         => $this->_counters['yandexMetrika'],
            'decorators'    => array($textAreaDecorator)
        )));

        $this->addElement($this->createElement('text', 'googleAnalytics', array(
            'required'      => false,
            'label'         => 'googleAnalytics',
            'class'         => 'span12',
            'value'         => $this->_counters['googleAnalytics'],
            'decorators'    => array($textAreaDecorator)
        )));


        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'mainSettingsSave',
            'decorators' => array($buttonDecorator)
        )));
    }
}
