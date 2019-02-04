<?php

class ItRocks_Form_Decorator_AdminCheckbox extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="control-group %s">
            <label class="control-label">%s:</label>
            <div class="controls">
                <input type="checkbox" name="%s" %s class="checkbox" />
                <br />
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
        
        $name = $view->escape($element->getFullyQualifiedName());
        $label = $view->escape($element->getLabel());
        $id = $view->escape($element->getId());
        $class = $view->escape($element->getAttrib('class'));
        $value = $view->escape($element->getValue());
        $errors = $element->getMessages();
        
        $markup = sprintf(
            $this->_format,
            (count($errors) > 0) ? 'error' : '',
            $view->translate($label),
            $name,
            ($value == 1) ? 'checked="checked"' : '',
            (empty($errors)) ? '' : sprintf($this->_errorsFormat, $this->_formatErrors($errors))
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
