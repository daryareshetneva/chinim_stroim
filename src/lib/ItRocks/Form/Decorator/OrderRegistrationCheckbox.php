<?php

class ItRocks_Form_Decorator_OrderRegistrationCheckbox extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <input type="checkbox" name="%s" class="checkbox order-reg" %s id="reg">
        <label class="control-label order-reg" for="reg">%s</label>    
        %s
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
            $name,
            ($value == 1) ? 'checked="checked"' : '',
            $view->translate($label),
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
