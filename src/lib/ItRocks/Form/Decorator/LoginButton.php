<?php

class ItRocks_Form_Decorator_LoginButton extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="row">
            <div class="col-md-6">
		       <span class="remember-box checkbox">
			       <a href="registration/index">%s</a>
			   </span>
			</div>
	        <div class="col-md-6">
	            <input type="submit" value="%s" name="%s" class="btn btn-primary pull-right push-bottom" data-loading-text="Loading...">
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
            $view->translate('userRegister'),
            $label,            
            $name
        );
        return $markup;
        
    }
}