<?php

class Portfolio_Form_AdminComment extends Zend_Form {

    protected $_comment = null;
    
    public function __construct(Zend_Db_Table_Row_Abstract $comment) {
        $this->_comment = $comment;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('enctype', 'multipart/form-data');

        $textDecorator = new ItRocks_Form_Decorator_AdminText;
        $textareaDecorator = new ItRocks_Form_Decorator_AdminTextarea;
        $selectDecorator = new ItRocks_Form_Decorator_AdminSelect;
        $buttonDecorator = new ItRocks_Form_Decorator_AdminSubmit;
        
        $this->addElement($this->createElement('text', 'username', array(
            'required' => true,
            'label' => 'portfolioUsername',
            'value' => $this->_comment->username,
            'readonly' => true,
            'class' => 'span8',
            'placeholder' => '',
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'email', array(
            'required' => true,
            'label' => 'portfolioEmail',
            'value' => $this->_comment->email,
            'readonly' => true,
            'class' => 'span8',
            'placeholder' => '',
            'decorators' => array($textDecorator)
        )));
        
        $this->addElement($this->createElement('select', 'status', array(
            'required' => true,
            'label' => 'portfolioCommentStatus',
            'value' => $this->_comment->status,
            'multiOptions' => array(
                0 => 'Disabled',
                1 => 'Active'
            ),
            'decorators' => array($selectDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'comment', array(
            'required' => true,
            'label' => 'portfolioComment',
            'value' => $this->_comment->comment,
            'class' => 'span8',
            'decorators' => array($textareaDecorator)
        )));
        
        $this->addElement($this->createElement('text', 'reply', array(
            'required' => false,
            'label' => 'reply',
            'value' => $this->_comment->reply,
            'class' => 'span8',
            'decorators' => array($textareaDecorator)
        )));
        
        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => 'portfolioEditButton',
            'decorators' => array($buttonDecorator)
        )));
        
    }
}
