<?php

class ItRocks_Form_Decorator_AdminText extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="control-group %s">
            <label class="control-label">%s:</label>
            <div class="controls">
                <input type="text" name="%s" value="%s" placeholder="%s" %s class="%s" />
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
        $placeholder = $element->getAttrib('placeholder');
        $class = $element->getAttrib('class');
        $readonly = $element->getAttrib('readonly');
        $value = $view->escape($element->getValue());
        $errors = $element->getMessages();
        
        $markup = sprintf(
            $this->_format,
            (count($errors) > 0) ? 'error' : '',
            $view->translate($label),
            $name,
            $value,
            ($placeholder) ? $view->translate($placeholder) : '',
            ($readonly) ? "readonly=''" : '',
            $class,
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
