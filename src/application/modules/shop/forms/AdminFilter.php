<?php

class Shop_Form_AdminFilter extends ItRocks_Form {

    protected $_filter        = array();

    public function __construct(Zend_Db_Table_Row_Abstract $filter) {
        $this->_filter = $filter;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('text', 'title', array(
            'required'      => true,
            'label'         => 'adminTitle',
            'value'         => $this->_filter->title,
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));


        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'save',
            'decorators'    => array($buttonDecorator)
        )));

    }

    public function isValid($data){
        $ret = parent::isValid($data);

        if ($this->_filter->title != $data['title'])
        {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'Shop_Filters',
                    'field' => 'title'
                )
            );
            if (!$aliasValidator->isValid($data['title']))
            {
                $this->title->addError("Фильтр с названием ".$data['title']. " существует. Придумайте другой вариант или переименуйте существующую запись.");
                $ret = false;
            }
        }

        return $ret;
    }
}