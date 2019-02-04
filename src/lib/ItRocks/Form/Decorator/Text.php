<?php

class ItRocks_Form_Decorator_Text extends Zend_Form_Decorator_Abstract {

    protected $_format = '
			<div class="col-md-6">
			<label class="control-label" for="%s">%s</label>
				<input type="text" name="%s" value="%s" placeholder="%s" class="form-control" id="%s">
                <br />
                %s
			</div>
        ';
    
    protected $_errorFormat = '
        <div class="alert alert-danger">
            <strong>%s</strong>
        </div>
    ';
	
	public function render($content) {
		$element = $this->getElement();
		$view    = $element->getView();
		if (null === $view) {
			return $content;
		}

        $name = $element->getFullyQualifiedName();
        $label = $element->getLabel();
        $value = $view->escape($element->getValue());
        $placeholder = $element->getAttrib('placeholder');
        $id = $element->getAttrib('id');
        $errors = $element->getMessages();

        
        $markup = sprintf(
            $this->_format,
            $id,
            $view->translate($label),
            $name,
            $value,
            $placeholder,
            $id,
            $this->_formatErrors($errors)
        );
        return $markup;
	}
    
    protected function _formatErrors($errors) {
        $errorString = '';
        foreach ($errors as $key => $error) {
            $errorString .= sprintf($this->_errorFormat, $error);
        }
        return $errorString;
    }
}
