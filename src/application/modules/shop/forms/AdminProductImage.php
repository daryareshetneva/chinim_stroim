<?php

class Shop_Form_AdminProductImage extends ItRocks_Form {

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');


        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $adminFileDecorator = new ItRocks_Form_Decorator_AdminFile();


        $this->addElement($this->createElement('file', 'image', array(
            'required' => true,
            'label' => 'productImage',
            'decorators' => array($adminFileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));


        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'save',
            'decorators'    => array($buttonDecorator)
        )));

    }

}