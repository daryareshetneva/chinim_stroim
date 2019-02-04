<?php

class ItRocks_Form_Decorator_HotelRoomsText extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="booking-form-item">        
            <input type="text" name="%s" value="%s" id="%s" placeholder="%s" class="%s" required />
            <span class="booking-form-error" id="%s-error"></span>
        </div>
        ';
    
    protected $_errorFormat = "
        <span class=\"help-block\">%s</span>\n\t
        ";
	
	public function render($content) {
		$element = $this->getElement();
		$view    = $element->getView();
		if (null === $view) {
			return $content;
		}

        
        $name = $element->getFullyQualifiedName();
        $label = $element->getLabel();
        $class = $element->getAttrib('class');
        $id = $element->getId();
        $value = $view->escape($element->getValue());
        
        $markup = sprintf(
            $this->_format,
            $name,
            $value,
            $id,
            $label,
            $class,
            $id
        );
        return $markup;
	}
    
    protected function _formatErrors($errors) {
        $errorString = '';
        foreach ($errors as $error) {
            $errorString .= sprintf($this->_errorFormat, $error);
        }
        return $errorString;
    }
}
