<?php

class ItRocks_Form_Decorator_LoginEmail extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="row">
	        <div class="form-group">
	           <div class="col-md-12">
		           <label>%s</label>
		           <input type="text" value="%s" name="%s" class="form-control input-lg">
                   %s
 	           </div>
	       </div>
	    </div>
    ';

    protected $_errorFormat = '
        <br />
        <div class="alert alert-danger">
            <strong>%s</strong>
        </div>
    ';

    public function render($content) {
        $element = $this->getElement();
        $view = $element->getView();
        if (null === $view) {
            return $content;
        }
        $translate = Zend_Registry::get('Root_Translate');
        
        $name = $element->getFullyQualifiedName();
        $label = $element->getLabel();
        $value = $view->escape($element->getValue());
        $errors = $element->getMessages();
        
        $errorsHtml = '';
        foreach ($errors as $code => $err) {
            $errorsHtml .= sprintf($this->_errorFormat, $translate->_($err));
        }

        $markup = sprintf(
            $this->_format,
            $label,
            $view->translate($value),
            $name,
            $errorsHtml
        );
        return $markup;
        
    }
}