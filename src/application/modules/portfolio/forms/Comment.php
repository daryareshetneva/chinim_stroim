<?php

class Portfolio_Form_Comment extends Zend_Form {
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $view = Zend_Layout::getMvcInstance()->getView();

        
        $textDecorator = new ItRocks_Form_Decorator_Text;
        $captchaDecorator = new ItRocks_Form_Decorator_Captcha;
        $textAreaDecorator = new ItRocks_Form_Decorator_TextArea;
        
        $this->addElement($this->createElement('text', 'username', array(
            'required' => true,
            'label' => 'portfolioUsername',
            'class' => 'form-control',
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'email', array(
            'required' => true,
            'label' => 'portfolioEmail',
            'class' => 'form-control',
            'validators' => array(
                'EmailAddress'
            ),
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'comment', array(
            'required' => true,
            'label' => 'portfolioComment',
            'class' => 'form-control',
            'decorators' => array($textAreaDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array(
            'label' => 'Отправить',
            'class' => 'btn btn-primary'
        )));
    }
}
