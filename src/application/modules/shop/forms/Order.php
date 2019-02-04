<?php

class Shop_Form_Order extends ItRocks_Form {
    protected $_order1 = [];
    protected $_user = [];

    public function __construct(Zend_Db_Table_Row_Abstract $order1, $user = []) {
        $this->_order1 = $order1;
        $this->_user = $user;
        parent::__construct();
    }

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator      = new ItRocks_Form_Decorator_CabinetText();
        $textareaDecorator = new ItRocks_Form_Decorator_CabinetTextArea();
        $selectDecorator = new ItRocks_Form_Decorator_CabinetSelect();
        $passwordDecorator  = new ItRocks_Form_Decorator_CabinetPassword();
        $buttonDecorator    = new ItRocks_Form_Decorator_CabinetButton();


        $phoneValidator     = new ItRocks_Validate_Phone();
        $mailValidator      = new Validator_Mail();

        $checkbox           = new ItRocks_Form_Decorator_OrderRegistrationCheckbox();

        $this->addElement($this->createElement('text', 'firstName', array(
            'required'      => true,
            'label'         => 'orderFirstName',
            'value'         => isset($this->_user['firstName']) ? $this->_user['firstName'] : '',
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'lastName', array(
            'required'      => true,
            'label'         => 'orderLastName',
            'value'         => isset($this->_user['lastName']) ? $this->_user['lastName'] : '',
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'patronymic', array(
            'required'      => false,
            'label'         => 'orderPatronymic',
            'value'         => isset($this->_user['patronymic']) ? $this->_user['patronymic'] : '',
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'phone', array(
            'required'      => true,
            'label'         => 'orderPhone',
            'value'         => isset($this->_user['phone']) ? $this->_user['phone'] : '',
            'readonly'      => false,
            'class'         => 'span12 phone',
            'placeholder'   => '',
            'decorators'    => array($textDecorator),
            'validators'    => [$phoneValidator]
        )));

        $this->addElement($this->createElement('text', 'email', array(
            'required'      => true,
            'label'         => 'orderEmail',
            'value'         => isset($this->_user['email']) ? $this->_user['email'] : '',
            'readonly'      => false,
            'class'         => 'span12',
            'placeholder'   => '',
            'decorators'    => array($textDecorator),
            'validators'    => [$mailValidator]
        )));

        $this->addElement($this->createElement('select', 'delivery', array(
            'required'      => true,
            'label'         => 'orderDelivery',
            'value'         => '',
            'multiOptions'  => [
                0 => 'Самовывоз',
                1 => 'Доставка'
            ],
            'decorators'    => array($selectDecorator)
        )));

        $this->addElement($this->createElement('select', 'userType', array(
            'required'      => true,
            'label'         => 'orderUserType',
            'value'         => '',
            'multiOptions'  => [
                0 => 'Физ. лицо',
                1 => 'Юр. лицо'
            ],
            'decorators'    => array($selectDecorator)
        )));

        if (empty($this->_user))
        {
            $this->addElement($this->createElement('checkbox', 'reg', array(
                'required'      => true,
                'checked'       => 0,
                'label'         => 'Создать аккаунт для последующего использования ',
                'readonly'      => false,
                'class'         => 'span12',
                'decorators'    => array($checkbox)
            )));

            $this->addElement($this->createElement('password', 'password', array(
                'required' => false,
                'type' => 'password',
                'label' => 'regPassword',
                'decorators' => array($passwordDecorator)
            )));

            $this->addElement($this->createElement('password', 'confirm', array(
                'required' => false,
                'type' => 'password',
                'label' => 'regConfirm',
                'decorators' => array($passwordDecorator)
            )));
        }


        $this->addElement($this->createElement('text', 'comment', array(
            'required'      => false,
            'label'         => 'orderComment',
            'value'         => '',
            'class'         => 'span12',
            'data-size'     => '160',
            'decorators'    => array($textareaDecorator)
        )));


        $this->addElement($this->createElement('submit', 'submit', array (
            'label'         => 'orderOk',
            'decorators'    => array($buttonDecorator)
        )));

    }

    public function isValid($data){
        $ret = parent::isValid($data);

        if ((isset($data['password'])) && (isset($data['confirm'])) && ($data['password'] !== $data['confirm'])) {
            $this->password->addError("Пароли не совпадают.");
            $this->confirm->addError("Пароли не совпадают.");
            $ret = false;
        }

        $shortTitleRegexp = "/^[a-zа-яё\s]+$/iu";

        if (!preg_match($shortTitleRegexp, $data['firstName'])) {
            $this->firstName->addError("Некорректное имя");
            $ret = false;
        }

        if (!preg_match($shortTitleRegexp, $data['lastName'])) {
            $this->lastName->addError("Некорретная фамилия");
            $ret = false;
        }

        if ($data['patronymic'] !== '') {
            if (!preg_match($shortTitleRegexp, $data['patronymic'])) {
                $this->patronymic->addError("Некорректное отчество");
                $ret = false;
            }
        }

        if (isset($data['reg']) && $data['reg'] == 'on')
        {
            // уникальность почтового ящика
            $uniqueEmail = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'Users',
                    'field' => 'email'
                )
            );
            if (!$uniqueEmail->isValid($data['email'])) {
                $this->email->addError("Почтовый ящик ".$data['email']." уже зарегистрирован.");
                $ret = false;
            }

            $this->password->setRequired(true);
            $this->confirm->setRequired(true);
            $this->reg->setValue(1);
        }

        return $ret;
    }

}