<?php

class User_Form_NewPassword extends Zend_Form {

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));
        $translator = Zend_Registry::get('Root_Translate');

        $textHtml5Decorator = new ItRocks_Form_Decorator_HTML5Text();
        $submitHtml5Decorator = new ItRocks_Form_Decorator_HTML5Submit();

        $notEmptyValidator = new Validator_NotEmpty();
        $passIdenticalValidator = new Validator_Identical(
                Zend_Controller_Front::getInstance()->getRequest()->getPost('password'),
                $translator->_('validatePasswordConfirm'));

        $this->addElement($this->createElement('password', 'password',
                        array(
                    'isRequired' => true,
                    'required' => true,
                    'type' => 'password',
                    'label' => 'regPassword',
                    'decorators' => array($textHtml5Decorator),
                    'validators' => array($notEmptyValidator)
        )));

        $this->addElement($this->createElement('password', 'confirm',
                        array(
                    'isRequired' => true,
                    'required' => true,
                    'type' => 'password',
                    'label' => 'regConfirm',
                    'decorators' => array($textHtml5Decorator),
                    'validators' => array(
                        $passIdenticalValidator, $notEmptyValidator)
        )));

        $this->addElement($this->createElement('submit', 'submit',
                        array(
                    'label' => 'save',
                    'class' => 'button',
                    'decorators' => array($submitHtml5Decorator)
        )));

        foreach ($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper');
        }
    }

}
