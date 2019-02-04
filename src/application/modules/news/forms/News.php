<?php

class News_Form_News extends ItRocks_Form {

    protected $_news = null;

    public function __construct(Zend_Db_Table_Row_Abstract $news) {
        $this->_news = $news;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs( array( 'class' => 'form-horizontal' ) );
        $textareaDecorator  = new ItRocks_Form_Decorator_AdminTextarea;
        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $submitDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();
        $dateDecorator      = new ItRocks_Form_Decorator_AdminDate;
        $aliasValidator     = new ItRocks_Validate_Alias();


        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'title',
            'class' => 'span8',
            'value' => $this->_news->title,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'date', array(
            'required' => true,
            'label' => 'newsDate',
            'id' => 'newsDate',
            'value' => (!empty($this->_news->date)) ? date('m/d/Y', strtotime($this->_news->date)) : date('m/d/Y'),
            'class' => 'span2',
            'decorators' => array($dateDecorator)
        )));

        $this->addElement($this->createElement('file', 'image', array(
            'required' => false,
            'label' => 'smallImage',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new News_Model_Images();
        $this->image->addFilter($imageHelper);
        if ($this->_news->image) {
            $this->image->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_news->image),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('textarea', 'description', array(
            'required' => true,
            'label' => 'description',
            'id' => 'newsDescription',
            'class' => 'span8',
            'value' => $this->_news->description,
            'decorators' => array($textareaDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'content', array(
            'required' => true,
            'label' => 'content',
            'id' => 'newsContent',
            'value' => $this->_news->content,
            'decorators' => array($textareaDecorator)
        )));

        $this->addElement($this->createElement('text', 'alias', array(
            'required' => false,
            'label' => 'alias',
            'class' => 'span8',
            'value' => $this->_news->alias,
            'decorators' => array($textDecorator),
            'validators' => array($aliasValidator)
        )));

        $this->addElement($this->createElement('text', 'metaTitle', array(
            'required' => false,
            'label' => 'metaTitle',
            'class' => 'span8',
            'value' => $this->_news->metaTitle,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaDescription', array(
            'required' => false,
            'label' => 'metaDescription',
            'class' => 'span8',
            'value' => $this->_news->metaDescription,
            'data-size' => '160',
            'decorators' => array($textareaDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'metaKeywords', array(
            'required' => false,
            'label' => 'metaKeywords',
            'value' => $this->_news->metaKeywords,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => '',
            'decorators' => array($textareaDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => (!empty($this->_news->title)) ? 'edit' : 'add',
            'decorators' => array($submitDecorator)
        )));

    }

    public function isValid($data){
        $ret = parent::isValid($data);

        if (empty($data['alias'])) {
            $transliterateModel = new Model_Transliterate();
            $data['alias'] = $transliterateModel->transliterate($data['title']);
        }

        if ($this->_news->alias != $data['alias'] || empty($this->_news->alias)) {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'News',
                    'field' => 'alias'
                )
            );
            if (!$aliasValidator->isValid($data['alias'])) {
                $this->alias->addError("Запись с ссылкой ".$data['alias']. " существует. Придумайте другой вариант или переименуйте существующую запись.");
                $ret = false;
            }
        }

        return $ret;

    }
}