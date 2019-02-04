<?php
class Services_Form_EditCategory extends Zend_Form {

    protected $_category = null;

    public function __construct(Zend_Db_Table_Row_Abstract $category) {
        $this->_category = $category;
        parent::__construct();
    }

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
            0 => 'Нет родителя'
        );

        foreach ($pairs as $key=>$value) {
            $newPairs[$key] = $value;
        }

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'class' => 'span6',
            'value' => $this->_category['title'],
            'label' => 'servicesName',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'description', array(
            'required' => false,
            'label' => 'servicesDescription',
            'id' => 'newsDescription',
            'value' => $this->_category['description'],
            'decorators' => array($textareaDecorator)
        )));
        $this->addElement($this->createElement('text', 'alias', array(
            'required' => true,
            'class' => 'span6',
            'value' => $this->_category['alias'],
            'label' => 'servicesAlias',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => false,
            'label' => 'servicesMetaTitle',
            'readonly' => false,
            'value' => $this->_category['metaTitle'],
            'class' => 'span8',
            'placeholder' => 'meta title',
            'data-size' => '60',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'metaDescription', array(
            'required' => false,
            'label' => 'servicesMetaDescription',
            'value' => $this->_category['metaDescription'],
            'class' => 'span8',
            'data-size' => '160',
            'decorators' => array($textareaDecorator)
        )));

        $this->addElement($this->createElement('file', 'categoryPhoto', array(
            'required' => ($this->_category['categoryPhoto']) ? false : true,
            'label' => 'servicesCategoryPhoto',
            'value' => $this->_category['categoryPhoto'],
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Services_Model_Images;
        $this->categoryPhoto->addFilter($imageHelper);
        if ($this->_category->categoryPhoto) {
            $this->categoryPhoto->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_category->categoryPhoto),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => 'edit',
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

        if ($data['alias'] != $this->_category['alias']) {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'Services_Tree',
                    'field' => 'alias'
                )
            );
            if (!$aliasValidator->isValid($data['alias'])) {
                $this->alias->addError("Запись с ссылкой " . $data['alias'] . " существует. Придумайте другой вариант или переименуйте существующую запись.");
                $ret = false;
            }
        }

        return $ret;
    }
}