<?php

class User_Form_Forget extends ItRocks_Form {

    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs(array('class' => 'form-horizontal'));

        $textDecorator = new ItRocks_Form_Decorator_LoginEmail;
        $buttonDecorator = new ItRocks_Form_Decorator_RegisterButton;

        $this->addElement($this->createElement('text', 'email',
            array(
                'isRequired' => true,
                'required' => true,
                'type' => 'email',
                'label' => 'forgetEmail',
                'decorators' => array($textDecorator)
            )));

        $this->addElement($this->createElement('submit', 'submit',
            array(
                'label' => 'forgetConfirm',
                'class' => 'button',
                'decorators' => array($buttonDecorator)
            )));

        foreach ($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper');
        }
    }

    public function isValid($data)
    {
        $ret = parent::isValid($data);

        $dbRecordExistValidator = new Zend_Validate_Db_RecordExists([
            'table' => 'Users',
            'field' => 'email'
        ]);

        if (!$dbRecordExistValidator->isValid($data['email'])) {
            $this->email->addError('emailNotExist');
            $ret = false;
        }


        return $ret;
    }

}
