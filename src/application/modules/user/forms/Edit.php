<?php

class User_Form_Edit extends ItRocks_Form  {

    protected $_user = null;

    public function __construct($user) {
        $this->_user = $user;
        parent::__construct();
    }

    public function init() {
        $auth = Zend_Auth::getInstance();

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
                'value' => $this->_user[db_Users::_EMAIL],
                'validators' => ['EmailAddress'],
                'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'firstName',
            array(
                'required' => false,
                'label' => 'userFirstName',
                'placeholder' => 'userFirstNamePlaceholder',
                'class' => 'input-xlarge',
                'value' => $this->_user[db_Users::_FIRST_NAME],
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('text', 'lastName',
            array(
                'required' => false,
                'label' => 'userLastName',
                'placeholder' => 'userLastNamePlaceholder',
                'class' => 'input-xlarge',
                'value' => $this->_user[db_Users::_LAST_NAME],
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('text', 'patronymic',
            array(
                'required' => false,
                'label' => 'userPatronymic',
                'placeholder' => 'userPatronymicPlaceholder',
                'class' => 'input-xlarge',
                'value' => $this->_user[db_Users::_PATRONYMIC],
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('text', 'phone',
            array(
                'required' => false,
                'label' => 'Телефон',
                'placeholder' => 'userPhone',
                'class' => 'input-xlarge',
                'value' => $this->_user[db_Users::_PHONE],
                'decorators' => array($textDecorator),
                'validators' => array($phoneValidator)
            )));

        if ($auth->getIdentity()->type == 'administrator' && 1 != $this->_user->id) {
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
                    'value' => $this->_user[db_Users::_TYPE],
                    'decorators' => array($selectDecorator)
                )));
        }

        $this->addElement($this->createElement('password', 'password',
            array(
                'required' => false,
                'label' => 'userPassword',
                'placeholder' => 'userNewPassword',
                'class' => 'input-xlarge',
                'decorators' => array($passDecorator)
            )));
        
        $this->addElement($this->createElement('text', 'lastVisitDate',
            array(
                'required' => true,
                'label' => 'userLastVisit',
                'placeholder' => 'userLastVisit',
                'class' => 'input-medium',
                'readonly' => true,
                'value' => date('d.m.Y H:i',
                    strtotime($this->_user[db_Users::_LAST_DATE])),
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('text', 'registrationDate',
            array(
                'required' => true,
                'label' => 'userRegistrationDate',
                'placeholder' => 'userRegistrationDate',
                'class' => 'input-medium',
                'readonly' => true,
                'value' => date('d.m.Y H:i',
                    strtotime($this->_user[db_Users::_REG_DATE])),
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('submit', 'submit',
            array(
                'label' => 'userEdit',
                'decorators' => array($submitButtonDecorator)
            )));


    }

}
