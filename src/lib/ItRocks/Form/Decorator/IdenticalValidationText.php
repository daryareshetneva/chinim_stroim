<?php

class ItRocks_Form_Decorator_IdenticalValidationText extends Zend_Form_Decorator_Abstract {

    protected $_message = null;
    
    protected $_comparable = null;
    
    protected $_format = '
        <div class= "element">
            <label>%s</label>
            <input type="text" name="%s" value="%s" class="%s" id="%s" %s/>
            <br />
           <div class="errors" id="errors-%s">
        <ul>
        %s
        </ul>
        </div>
        </div>';
    
    
    protected $_errorFormat = '
        <li id=%d>%s</li>
        ';
	
    public function setMessage($message)
    {
        $this->_message = $message;
     }
     
     public function setComparable($name) {
         $this->_comparable = $name;
     }
     
    public function render($content) 
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }
        $onhanges = array();
        $onchanges[] = "onchange=\"identical('{$this->_comparable}', '{$this->_message}', this)\"";
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $name = $view->escape($element->getFullyQualifiedName());
        $label = $view->escape($element->getLabel());
        $id = $view->escape($element->getId());
        $class = $view->escape($element->getAttrib('class'));
        $value = $view->escape($element->getValue());
        $errors = $element->getMessages();

        $markup = sprintf(
            $this->_format,
            $view->translate($label),
            $name,
            $value,
            $class,
            $id,
            implode(';', $onchanges),
            $name,
            (empty($errors)) ? '' :  $this->_formatErrors($errors)
        );

        return $markup;
    }
    
    protected function _formatErrors($errors) {
        $errorString = '';
        $i = 0;
        foreach ($errors as $error) {
            $errorString .= sprintf($this->_errorFormat, $i, $error);
            $i++;
        }
        return $errorString;
    }
}
