<?php

class ItRocks_Form_Decorator_OrderRegistrationPassword extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="order-password">
             <div class="controls">
                 <label>%s</label>
                 <input type="password" value="" name="%s" class="form-control">
                 %s
              </div>
	    </div>
    ';

    protected $_errorFormat = '
        <br>
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
        $errors = $element->getMessages();

        $errorsHtml = '';
        foreach ($errors as $code => $err) {
            $errorsHtml .= sprintf($this->_errorFormat, $translate->_($err));
        }

        $markup = sprintf(
            $this->_format,
            $label,
            $name,
            $errorsHtml
        );
        return $markup;

    }
}