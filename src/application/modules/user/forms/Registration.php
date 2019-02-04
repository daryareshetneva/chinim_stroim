<?php

class User_Form_Registration extends ItRocks_Form {

    protected $_phoneRegex = '/^((8|\+7)?[\-]?[\(]?(\d{3})[\)]?)[\-]?((\d{3})[\-]?(\d{2}\-?\d{2}))$/';

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $translator = Zend_Registry::get('Root_Translate');

        $textDecorator = new ItRocks_Form_Decorator_LoginEmail;
        $registerButtonDecorator = new ItRocks_Form_Decorator_RegisterButton;
        $phoneDecorator = new ItRocks_Form_Decorator_RegisterPhone;
        $passwordDecorator = new ItRocks_Form_Decorator_RegisterPassword;

        $alnumValidator = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));        

        $this->addElement($this->createElement('text', 'lastName', array(
            'required' => true,
            'type' => 'text',
            'label' => 'regLastName',
            'validators' => ['Alpha'],
            'decorators' => array($textDecorator),
        )));
        $this->lastName->addValidator($alnumValidator);

        $this->addElement($this->createElement('text', 'firstName', array(
            'required' => true,
            'type' => 'text',
            'label' => 'regFirstName',
            'validators' => ['Alpha'],
            'decorators' => array($textDecorator),
        )));
        $this->firstName->addValidator($alnumValidator);

        $this->addElement($this->createElement('text', 'patronymic', array(
            'required' => false,
            'type' => 'text',
            'label' => 'regPatronymic',
            'validators' => ['Alpha'],
            'decorators' => array($textDecorator),
        )));
        $this->patronymic->addValidator($alnumValidator);
        

        $this->addElement($this->createElement('text', 'email', array(
            'required' => true,
            'type' => 'email',
            'label' => 'regEmail',
            'decorators' => array($textDecorator),
            'validators' => array(array('EmailAddress'))
        )));

        $this->addElement($this->createElement('text', 'phone', array(
            'required' => true,
            'label' => 'regPhone',
            'inputId' => 'phone',
            'placeholder' => '+79231232211',
            'decorators' => array($phoneDecorator)
        )));

        $this->addElement($this->createElement('password', 'password', array(
            'required' => true,
            'type' => 'password',
            'label' => 'regPassword',
            'decorators' => array($passwordDecorator)
        )));

        $this->addElement($this->createElement('password', 'confirm', array(
            'required' => true,
            'type' => 'password',
            'label' => 'regConfirm',
            'decorators' => array($passwordDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array(
            'label' => 'regRegistrate',
            'class' => 'button',
            'decorators' => array($registerButtonDecorator)
        )));

    }

    public function isValid($data) {
        $res = parent::isValid($data);
        $phoneValidator = new Zend_Validate_Regex(array('pattern' => $this->_phoneRegex));
        if (!$phoneValidator->isValid($data['phone']) && !empty($data['phone'])) {
            $this->phone->addError('phoneRegexNotMatch');
            $res = false;
        }

        $dbRecordNotExistValidator = new Zend_Validate_Db_NoRecordExists(array(
            'table' => 'Users',
            'field' => 'email'
        ));

        if (!$dbRecordNotExistValidator->isValid($data['email'])) {
            $this->email->addError('emailAlreadyExist');
            $res = false;
        }

        if (strlen($data['password']) < 4) {
            $res = false;
            $this->password->addError('passwordsLengthError');
        }

        if ($data['password'] != $data['confirm']) {
            $res = false;
            $this->password->addError('passwordsNotMatch');
            $this->confirm->addError('passwordsNotMatch');
        }

        return $res;
    }
}
