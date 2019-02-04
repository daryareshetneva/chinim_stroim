<?php

class User_Form_Add extends ItRocks_Form
{
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));

        $textDecorator          = new ItRocks_Form_Decorator_AdminText;
        $passDecorator          = new ItRocks_Form_Decorator_AdminPassword();
        $selectDecorator        = new ItRocks_Form_Decorator_AdminSelect();
        $submitButtonDecorator  = new ItRocks_Form_Decorator_AdminSubmit;
        $phoneValidator         = new ItRocks_Validate_Phone();

        $translate = $this->getTranslator();

        $this->addElement($this->createElement('text', 'email',
            array(
                'required' => true,
                'label' => 'userEmail',
                'placeholder' => 'userEmail',
                'class' => 'input-large',
                'value' => '',
                'validators' => ['EmailAddress'],
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('text', 'firstName',
            array(
                'required' => false,
                'label' => 'userFirstName',
                'placeholder' => 'userFirstNamePlaceholder',
                'class' => 'input-xlarge',
                'value' => '',
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('text', 'lastName',
            array(
                'required' => false,
                'label' => 'userLastName',
                'placeholder' => 'userLastNamePlaceholder',
                'class' => 'input-xlarge',
                'value' => '',
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('text', 'patronymic',
            array(
                'required' => false,
                'label' => 'userPatronymic',
                'placeholder' => 'userPatronymicPlaceholder',
                'class' => 'input-xlarge',
                'value' => '',
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('text', 'phone',
            array(
                'required' => false,
                'label' => 'Телефон',
                'placeholder' => 'userPhone',
                'class' => 'input-xlarge',
                'value' => '',
                'decorators' => array($textDecorator),
                'validators' => array($phoneValidator)
            )));

        $this->addElement($this->createElement('select', 'type',
            array(
                'required' => false,
                'label' => 'userType',
                'multiOptions' => [
                    'administrator' => $translate->_('userType_administrator'),
                    'manager' => $translate->_('userType_manager'),
                    'user' => $translate->_('userType_user')
                ],
                'class' => 'input-xlarge',
                'value' => '',
                'decorators' => array($selectDecorator)
            )));

        $this->addElement($this->createElement('password', 'password',
            array(
                'required' => true,
                'label' => 'userPassword',
                'placeholder' => 'userNewPassword',
                'class' => 'input-xlarge',
                'decorators' => array($passDecorator)
            )));

        $this->addElement($this->createElement('submit', 'submit',
            array(
                'label' => 'userAdd',
                'decorators' => array($submitButtonDecorator)
            )));
    }
}