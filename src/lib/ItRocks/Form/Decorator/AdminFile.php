<?php

class ItRocks_Form_Decorator_AdminFile extends Zend_Form_Decorator_File {

    protected $_format = '
        <div class="control-group %s">
            <label class="control-label">%s:</label>
            <div class="controls">
                <input type="file" name="%s" class="styled" size="24" style="opacity: 0;">
                %s
                %s
            </div>
        </div>
        ';
    
    protected $_errorFormat = "
        <span class=\"help-block\">%s</span>\n\t
        ";
    
    protected $_imgFormat = "
        <div class=\"view\"><img src=\"%s\" alt=\"img\"/></div>\n\t
        ";
	
    public function render($content) {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $name = $element->getFullyQualifiedName();
        $label = $element->getLabel();
        $errors = $element->getMessages();
        $image = $element->getAttrib('image');
        if ($image) {
            $img = sprintf($this->_imgFormat, $image);;
        } else {
            $img = '';
        }

        $markup = sprintf(
                $this->_format,
                (count($errors) > 0) ? 'error' : '',
                $view->translate($label),
                $name,
                (empty($errors)) ? '' : $this->_formatErrors($errors),
                $img
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
