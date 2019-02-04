<?php
class Services_Form_EditService extends Zend_Form {

    protected $_item = null;

    public function __construct(Zend_Db_Table_Row_Abstract $item) {
        $this->_item = $item;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');

        $textDecorator = new ItRocks_Form_Decorator_AdminText();
        $selectDecorator = new ItRocks_Form_Decorator_AdminSelect();
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $buttonDecorator = new ItRocks_Form_Decorator_AdminSubmit();
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();

        $model = new Services_Model_DbTable_Tree();
        $pairs = $model->getPairs();
        $newPairs = array();

        foreach ($pairs as $key=>$value) {
            $newPairs[$key] = $value;
        }

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'servicesName',
            'readonly' => false,
            'class' => 'span8',
            'value' => $this->_item['title'],
            'placeholder' => 'Roof leaks...',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'price', array(
            'required' => false,
            'label' => 'servicesPrice',
            'readonly' => false,
            'class' => 'span8',
            'value' => $this->_item['price'],
            'placeholder' => '100',
            'decorators' => array($textDecorator)
        )));


        $this->addElement($this->createElement('select', 'category_id', array(
            'required' => true,
            'multiOptions' => $newPairs,
            'value' => $this->_item['category_id'],
            'label' => 'serviceCategory',
            'decorators' => array($selectDecorator)
        )));


        $this->addElement($this->createElement('text', 'description', array(
            'required' => true,
            'label' => 'servicesDescription',
            'class' => 'span8',
            'value' => $this->_item['description'],
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'alias', array(
            'required' => false,
            'label' => 'servicesAlias',
            'readonly' => false,
            'class' => 'span8',
            'value' => $this->_item['alias'],
            'placeholder' => 'service_url_display',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'meta_title', array(
            'required' => false,
            'label' => 'servicesMetaTitle',
            'readonly' => false,
            'class' => 'span8',
            'value' => $this->_item['meta_title'],
            'placeholder' => 'meta Title...',
            'data-size' => '60',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'meta_description', array(
            'required' => false,
            'label' => 'servicesMetaDescription',
            'readonly' => false,
            'class' => 'span8',
            'value' => $this->_item['meta_description'],
            'placeholder' => 'meta Description...',
            'data-size' => '160',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('file', 'serviceMainPhoto', array(
            'required' => ($this->_item['serviceMainPhoto']) ? false : true,
            'label' => 'serviceMainPhoto',
            'value' => $this->_item['serviceMainPhoto'],
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Services_Model_Images;
        $this->serviceMainPhoto->addFilter($imageHelper);
        if ($this->_item->serviceMainPhoto) {
            $this->serviceMainPhoto->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_item->serviceMainPhoto),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'edit',
            'decorators' => array($buttonDecorator)
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

        if (!empty($_FILES['image']['name'][0])) {
            for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                $filename = $_FILES['image']['name'][$i];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!in_array($ext, array('jpg', 'jpeg', 'png'))) {
                    throw new Exception('wrongFileExtension');
                    return false;
                }
            }
        }

        if (empty($data['alias'])) {
            $transliterateModel = new Model_Transliterate();
            $data['alias'] = $transliterateModel->transliterate($data['title']);
        }

        if ($data['alias'] != $this->_item['alias']) {
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