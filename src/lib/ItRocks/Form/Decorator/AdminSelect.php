<?php

class ItRocks_Form_Decorator_AdminSelect extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="control-group %s">
            <label class="control-label">%s:</label>
            <div class="controls">
                <select id="%s" name="%s" class="styled %s" style="opacity: 1;">
                %s
                </select>
                %s
            </div>
        </div>
        ';
    protected $_errorFormat = "
        <span class=\"help-block\">%s</span>\n\t
        ";
    protected $_optionFormat = "<option value='%s'>%s</option>";
    protected $_selectedOptionFormat = "<option value='%s' selected='selected'>%s</option>";
    
    public function render($content) {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
                return $content;
        }

        $name = $view->escape($element->getFullyQualifiedName());
        $label = $view->escape($element->getLabel());
        $class = $view->escape($element->getAttrib('class'));
        $value = $view->escape($element->getValue());
        $multiOptions = $element->getMultiOptions();

        $errors = $element->getMessages();

        $markup = sprintf(
                $this->_format,
                (count($errors) > 0) ? 'error' : '',
                $view->translate($label),
                $name,
                $name,
                $class,
                $this->_fillOptions($multiOptions, $value),
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
    
    /**
     * Fill options for select element
     * 
     * @param array $options Array of elements with $key => $value
     * @return string
     */
    protected function _fillOptions($options, $selectedValue) {
        $optionsString = '';
        foreach ($options as $key => $value) {
            if ($key == $selectedValue) {
                $optionsString .= sprintf($this->_selectedOptionFormat, $key, $value);
            } else {
                $optionsString .= sprintf($this->_optionFormat, $key, $value);
}
        }
        return $optionsString;
    }
}
