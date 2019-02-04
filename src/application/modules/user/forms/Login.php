<?php

class User_Form_Login extends Zend_Form {
    
     
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        
        $loginEmailDecorator = new ItRocks_Form_Decorator_LoginEmail;
        $loginPasswordDecorator = new ItRocks_Form_Decorator_LoginPassword;
        $loginButtonDecorator = new ItRocks_Form_Decorator_LoginButton;
        
        $this->addElement($this->createElement('text', 'email', array(
            'required' => true,
            'label' => 'loginName',
            'type' => 'email',
            'decorators' => array($loginEmailDecorator)
        )));

        $this->addElement($this->createElement('password', 'password', array(
            'required' => true,
            'label' => 'loginPass',
            'type' => 'password',
            'decorators' => array($loginPasswordDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'loginSubmit',
            'decorators' => array($loginButtonDecorator)
        )));
        
    }
}