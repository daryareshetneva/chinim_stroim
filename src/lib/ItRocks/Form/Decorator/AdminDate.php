<?php

class ItRocks_Form_Decorator_AdminDate extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="control-group %s">
            <label class="control-label">%s:</label>
            <div class="controls">
                <input type="text" name="%s" value="%s" class="%s" id="%s" />
                %s
            </div>
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
        $errors = $element->getMessages();
        
        $markup = sprintf(
            $this->_format,
            (count($errors) > 0) ? 'error' : '',
            $view->translate($label),
            $name,
            $value,
            $class,
            $id,
            (empty($errors)) ? '' : $this->_formatErrors($errors)
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
