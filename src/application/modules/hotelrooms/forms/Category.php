<?php

class HotelRooms_Form_Category extends Zend_Form {

    protected $_category = null;

    public function __construct(Zend_Db_Table_Row_Abstract $category) {
        $this->_category = $category;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs( array( 'class' => 'form-horizontal' ) );

        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $submitDecorator    = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'Название',
            'class' => 'span8',
            'value' => $this->_category->title,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => (!empty($this->_category->title)) ? 'Редактировать' : 'Добавить',
            'decorators' => array($submitDecorator)
        )));
    }

    public function isValid($data){
        $ret = parent::isValid($data);

        $transliterateModel = new Model_Transliterate();
        $data['alias'] = $transliterateModel->transliterate($data['title']);


        if ($this->_category->alias != $data['alias'] || empty($this->_category->alias)) {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'HotelRooms_Categories',
                    'field' => 'alias'
                )
            );
            if (!$aliasValidator->isValid($data['alias'])) {
                $this->title->addError("Такая категория уже существует. Придумайте другой вариант.");
                $ret = false;
            }
        }

        return $ret;

    }
}