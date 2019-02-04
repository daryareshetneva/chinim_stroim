<?php
class Services_Form_AddCategory extends Zend_Form {

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');

        $textDecorator = new ItRocks_Form_Decorator_AdminText();
        $textareaDecorator = new ItRocks_Form_Decorator_AdminTextarea();
        $selectDecorator = new ItRocks_Form_Decorator_AdminSelect();
        $sbmtDecorator = new ItRocks_Form_Decorator_AdminSubmit();
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();

        $model = new Services_Model_DbTable_Tree();
        $pairs = $model->getPairs();
        $newPairs = array(
            0 => 'Корневая запись'
        );

        foreach ($pairs as $key=>$value) {
            $newPairs[$key] = $value;
        }

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'class' => 'span6',
            'label' => 'servicesName',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('select', 'parent_id', array(
            'required' => true,
            'multiOptions' => $newPairs,
            'label' => 'servicesCategoryParent',
            'decorators' => array($selectDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'description', array(
            'required' => false,
            'label' => 'servicesDescription',
            'id' => 'newsDescription',
            'decorators' => array($textareaDecorator)
        )));
        

        $this->addElement($this->createElement('hidden', 'position', array(
            'value' => '0'
        )));

        $this->addElement($this->createElement('text', 'alias', array(
            'required' => false,
            'class' => 'span6',
            'label' => 'servicesAlias',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => false,
            'label' => 'servicesMetaTitle',
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'meta title',
            'data-size' => '60',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaDescription', array(
            'required' => false,
            'label' => 'servicesMetaDescription',
            'class' => 'span8',
            'data-size' => '160',
            'decorators' => array($textareaDecorator)
        )));

        $this->addElement($this->createElement('file', 'categoryPhoto', array(
            'required' => true,
            'label' => 'servicesCategoryPhoto',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => 'create',
            'decorators' => array($sbmtDecorator),
            'type' => 'submit'
        )));
    }

    public function isValid($data) {
        $ret = parent::isValid($data);

        $shortTitleRegexp = "!^[a-z_\-.0-9]*$!i";
        $value = $data['alias'];
        if (!preg_match($shortTitleRegexp, $value)) {
            $this->alias->addError('wrongAliasTitle');
            $ret = false;
        }

        if (empty($data['alias'])) {
            $transliterateModel = new Model_Transliterate();
            $data['alias'] = $transliterateModel->transliterate($data['title']);
        }


        $aliasValidator = new Zend_Validate_Db_NoRecordExists(
            array(
                'table' => 'Services_Tree',
                'field' => 'alias'
            )
        );
        if (!$aliasValidator->isValid($data['alias'])) {
            $this->alias->addError("Запись с ссылкой ".$data['alias']. " существует. Придумайте другой вариант или переименуйте существующую запись.");
            $ret = false;
        }


        return $ret;
    }
}