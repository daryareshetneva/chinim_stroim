<?php

class ItRocks_Form_Decorator_TextArea extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="form-group">
			<div class="col-md-12">
			<label for="%s" class=" control-label">%s</label>
			    <textarea name="%s" maxlength="%s" data-plugin-maxlength="" rows="3" class="form-control" style="height: 103px;">%s</textarea>		
                <br>%s
			 </div>
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
        $id = $element->getAttrib('id');
        $maxLength = ($element->getAttrib('maxLength')) ? $element->getAttrib('maxLength') : 250;
        $errors = $element->getMessages();


        $markup = sprintf(
            $this->_format,
            $id,
            $view->translate($label),
            $name,
            $maxLength,
            $value,
            $this->_formatErrors($errors)
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
