<?php

class Static_Form_Static extends ItRocks_Form {
    
    protected $_page = null;
    
    public function __construct(Zend_Db_Table_Row_Abstract $page) {
        $this->_page = $page;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $textAreaDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $submitButtonDecorator = new ItRocks_Form_Decorator_AdminSubmit;
        $aliasValidator     = new ItRocks_Validate_Alias();
        
        if (($this->_page['alias'] != 'home') && ($this->_page['alias'] != 'about') && ($this->_page['alias'] != 'contacts') && ($this->_page['alias'] != 'stoimost-uslug')) {
            $this->addElement($this->createElement('text', 'alias', array(
                'required' => true,
                'label' => 'staticAdminEditAlias',
                'value' => $this->_page['alias'],
                'readonly' => false,
                'class' => 'span12',
                'placeholder' => 'staticAdminEditAlias',
                'validators' => array($aliasValidator),
                'decorators' => array($textDecorator)
            )));
        }
        
        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'staticAdminEditTitle',
            'value' => $this->_page['title'],
            'readonly' => false,
            'class' => 'input-xlarge span12',
            'placeholder' => 'staticAdminEditTitle',
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => true,
            'label' => 'staticAdminEditMetaTitle',
            'value' => $this->_page['metaTitle'],
            'readonly' => false,
            'class' => 'input-xlarge span12',
            'placeholder' => 'staticAdminEditMetaTitle',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaDescription', array(
            'label' => 'staticAdminEditMetaDescription',
            'value' => $this->_page['metaDescription'],
            'class' => 'input-xlarge span12',
            'decorators' => array($textAreaDecorator)
        )));
        
        $this->addElement($this->createElement('textarea', 'staticContent', array(
            'label' => 'staticAdminEditContent',
            'id' => 'pageContent',
            'value' => $this->_page['content'],
            'class' => 'input-xlarge span12',
            'decorators' => array($textAreaDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'staticAdminUpdate',
            'decorators' => array($submitButtonDecorator)
        )));
        
    }

    public function isValid($data){
        $ret = parent::isValid($data);

        if ($this->_page->alias != $data['alias']) {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'Static',
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