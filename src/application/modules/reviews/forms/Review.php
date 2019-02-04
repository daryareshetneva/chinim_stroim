<?php

class Reviews_Form_Review extends Zend_Form {

    protected $_review = null;
    
    public function __construct(Zend_Db_Table_Row_Abstract $review) {
        $this->_review = $review;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        
        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $dateDecorator      = new ItRocks_Form_Decorator_AdminDate;
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'reviewTitle',
            'value' => $this->_review->title,
            'readonly' => false,
            'class' => 'span8',
            'placeholder' => 'Иоган Себастьян Бах',
            'decorators' => array($textDecorator)
        )));


        $this->addElement($this->createElement('text', 'date', array(
            'required' => true,
            'label' => 'reviewDate',
            'id' => 'reviewDate',
            'value' => (!empty($this->_review->reviewDate)) ? date('m/d/Y', strtotime($this->_review->reviewDate)) : date('m/d/Y'),
            'class' => 'span2',
            'decorators' => array($dateDecorator)
        )));

        $this->addElement($this->createElement('text', 'answer', array(
            'required' => true,
            'label' => 'reviewAnswer',
            'value' => $this->_review->answer,
            'class' => 'span8',
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('file', 'photo', array(
            'required' => ($this->_review->photo) ? false : true,
            'label' => 'reviewPhoto',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Reviews_Model_Images();
        $this->photo->addFilter($imageHelper);
        if ($this->_review->photo) {
            $this->photo->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_review->photo),
                'imageAlternate' => ''
            )));
        }


        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => ($this->_review->title) ? 'edit' : 'add',
            'decorators' => array($buttonDecorator)
        )));

    }
}
