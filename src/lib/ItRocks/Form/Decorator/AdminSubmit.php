<?php

class ItRocks_Form_Decorator_AdminSubmit extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="control-group">
            <button class="btn btn-primary" type="submit">%s</button>
        </div>
        ';
    
	
	public function render($content) {
		$element = $this->getElement();
		$view    = $element->getView();
		if (null === $view) {
			return $content;
		}
            
        $label = $element->getLabel();
        
        $markup = sprintf(
            $this->_format,
            $view->translate($label)
        );
        return $markup;
	}
    
}
