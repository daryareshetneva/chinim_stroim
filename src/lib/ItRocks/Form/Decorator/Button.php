<?php

class ItRocks_Form_Decorator_Button extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="form-group">
            <div class="col-md-2">
	            <input type="submit" value="%s" name="%s" class="btn btn-primary pull-right push-bottom">
	        </div>
		</div>
    ';

    protected $_errorFormat = "
        <span class=\"col-sm-5 popover right\"><i class=\"arrow\"></i>%s</span>\n\t
    ";

    public function render($content) {
        $element = $this->getElement();
        $view = $element->getView();
        if (null === $view) {
            return $content;
        }
        
        $name = $element->getFullyQualifiedName();
        $label = $element->getLabel();
        $errors = $element->getMessages();

        $markup = sprintf(
            $this->_format,            
            $view->translate($label),
            $name
        );
        return $markup;
        
    }
}