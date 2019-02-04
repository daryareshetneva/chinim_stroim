<?php

class Reviews_Form_Admin extends Zend_Form {
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        
        $this->addElement($this->createElement('textarea', 'answer', array(
            'required' => true,
            'label' => 'answer',
            'id' => 'reviewsAnswer',
        )));

        $this->addElement($this->createElement('button', 'submit', array (
            'label' => 'reply',
            'type' => 'submit'
        )));
    }
}