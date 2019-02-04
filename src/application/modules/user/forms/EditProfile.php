<?php

class User_Form_EditProfile extends ItRocks_Form {
    protected $_user = null;

    public function __construct($user) {
        $this->_user = $user;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator = new ItRocks_Form_Decorator_LoginEmail;

        $phoneValidator     = new ItRocks_Validate_Phone();
        $phoneDecorator     = new ItRocks_Form_Decorator_RegisterPhone;
        $mailValidator      = new Validator_Mail();
        $passwordDecorator  = new ItRocks_Form_Decorator_OrderRegistrationPassword();
        $buttonDecorator    = new ItRocks_Form_Decorator_AdminSubmit();

        $passwordDecorator  = new ItRocks_Form_Decorator_RegisterPassword;

        $this->addElement($this->createElement('text', 'firstName', array(
            'required'      => true,
            'label'         => 'regFirstName',
            'value'         => isset($this->_user['firstName']) ? $this->_user['firstName'] : '',
            'readonly'      => false,
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'lastName', array(
            'required'      => true,
            'label'         => 'regLastName',
            'value'         => isset($this->_user['lastName']) ? $this->_user['lastName'] : '',
            'readonly'      => false,
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'patronymic', array(
            'required'      => false,
            'label'         => 'regPatronymic',
            'value'         => isset($this->_user['patronymic']) ? $this->_user['patronymic'] : '',
            'readonly'      => false,
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'phone', array(
            'required'      => true,
            'label'         => 'regPhone',
            'value'         => isset($this->_user['phone']) ? $this->_user['phone'] : '',
            'readonly'      => false,
            'inputId'       => 'phone',
            'placeholder'   => '+79231232211',
            'decorators'    => array($phoneDecorator),
            'validators'    => [$phoneValidator]
        )));

        $this->addElement($this->createElement('text', 'email', array(
            'required'      => true,
            'label'         => 'regEmail',
            'value'         => isset($this->_user['email']) ? $this->_user['email'] : '',
            'readonly'      => true,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator),
            'validators'    => [$mailValidator]
        )));

        $this->addElement($this->createElement('password', 'currentPassword', array(
            'required' => false,
            'type' => 'password',
            'label' => 'Старый пароль',
            'decorators' => array($passwordDecorator)
        )));

        $this->addElement($this->createElement('password', 'password', array(
            'required' => false,
            'type' => 'password',
            'label' => 'Новый пароль',
            'decorators' => array($passwordDecorator)
        )));

        $this->addElement($this->createElement('password', 'confirm', array(
            'required' => false,
            'type' => 'password',
            'label' => 'Повторите новый пароль',
            'decorators' => array($passwordDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'editSave',
            'decorators'    => array($buttonDecorator)
        )));

    }

    public function isValid($data){
        $ret = parent::isValid($data);

        if ($data['password'] !== $data['confirm']) {
            $this->password->addError("Пароли не совпадают.");
            $this->confirm->addError("Пароли не совпадают.");
            $ret = false;
        }

        $shortTitleRegexp = "/^[a-zа-яё\s]+$/iu";

        if (!preg_match($shortTitleRegexp, $data['firstName'])) {
            $this->firstName->addError("Невалидное имя");
            $ret = false;
        }

        if (!preg_match($shortTitleRegexp, $data['lastName'])) {
            $this->lastName->addError("Невалидная фамилия");
            $ret = false;
        }

        if ($data['patronymic'] !== '') {
            if (!preg_match($shortTitleRegexp, $data['patronymic'])) {
                $this->patronymic->addError("Невалидное отчество");
                $ret = false;
            }
        }

        if (($data['password'] !== '') && (strlen($data['password']) < 4)){
            $this->password->addError("Пароль должен состоять минимум из 4 символов.");
            $this->confirm->addError("Пароль должен состоять минимум из 4 символов.");
            $ret = false;
        }

        if (($data['currentPassword'] !== '') && ($this->_user['password'] !== md5($data['currentPassword'])))
        {
            $this->currentPassword->addError("Неправильный пароль");
            $this->password->setRequired(true);
            $this->confirm->setRequired(true);
            $ret = false;
        }

        return $ret;
    }

}