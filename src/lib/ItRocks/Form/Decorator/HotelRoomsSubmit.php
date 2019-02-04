<?php

class ItRocks_Form_Decorator_HotelRoomsSubmit extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="booking-form-item">
            <button type="submit" class="btn booking-form-btn">%s</button>
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
