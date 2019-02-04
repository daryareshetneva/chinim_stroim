<?php

class Certificates_Form_Certificates extends Zend_Form {

    protected $_certificate = null;

    public function __construct(Zend_Db_Table_Row_Abstract $certificate)
    {
        $this->_certificate = $certificate;
        parent::__construct();
    }

    public function init()
    {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');

        $textDecorator      = new ItRocks_Form_Decorator_AdminText();
        $dateDecorator      = new ItRocks_Form_Decorator_AdminDate();
        $textareadDecorator = new ItRocks_Form_Decorator_AdminTextarea();
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit();

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'certificateTitle',
            'value' => $this->_certificate->title,
            'class' => 'span8',
            'placeholder' => 'Сертификат ни о чем',
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('file', 'image', array(
            'required' => ($this->_certificate->image) ? false : true,
            'label' => 'certificateImage',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new Certificates_Model_Images();
        $this->image->addFilter($imageHelper);
        if ($this->_certificate->image) {
            $this->image->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_certificate->image),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('textarea', 'description', array(
            'required' => true,
            'label' => 'certificateDescription',
            'id' => 'description',
            'value' => $this->_certificate->description,
            'decorators' => array($textareadDecorator)
        )));

        $this->addElement($this->createElement('text', 'date', array(
            'label' => 'certificateDate',
            'id' => 'certificateDate',
            'value' => (!empty($this->_certificate->date)) ? date('m/d/Y', strtotime($this->_certificate->date)) : date('m/d/Y'),
            'class' => 'span2',
            'decorators' => array($dateDecorator)
        )));


        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => ($this->_certificate->title) ? 'edit' : 'add',
            'decorators' => array($buttonDecorator)
        )));

    }
}