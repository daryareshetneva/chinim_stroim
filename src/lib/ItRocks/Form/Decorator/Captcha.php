<?php

class ItRocks_Form_Decorator_Captcha extends Zend_Form_Decorator_Abstract {

    protected $_format = "
        <div class='form-group'>
            <label class='col-md-12 control-label' >%s</label>
            <div class='col-md-12'>
                <div class='g-recaptcha' data-sitekey='6LdOEAwUAAAAAOH2bomf9ST-nS8hPjRk6jvbkJ7z'></div>
            </div>
        </div>
        %s
        ";

    protected $_errorsFormat = '
        <br />
        <div class="alert alert-danger small">
            %s
        </div>
        </ul>';
    
    protected $_errorFormat = '
        %s<br />
        ';
	
    public function render($content) 
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
         }
        $label = $element->getLabel();
        $id = htmlentities($element->getId());
        $class = $element->getAttrib('class');
        $value = $element->getValue();
        $errors = $element->getMessages();
        $translate = Zend_Registry::get('Zend_Translate');
        $markup = sprintf(
            $this->_format,
            $translate->_($element->getLabel()),
            (empty($errors)) ? '' : sprintf($this->_errorsFormat, $this->_formatErrors($errors))
        );
        return $markup;
    }
    
    protected function _formatErrors($errors) 
    {
        $errorString = '';
        foreach ($errors as $error) {
            $errorString .= sprintf($this->_errorFormat, $error);
        }
        return $errorString;
    }
}
