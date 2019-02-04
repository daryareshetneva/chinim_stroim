<?php

class ItRocks_Form_Decorator_FrontInlineSubmit extends Zend_Form_Decorator_Abstract {

    protected $_format = '
	<div class="col-sm-5">
		<button type="submit" class="button blue-button">%s</button>
	</div>
        ';

    public function render( $content ) {
        $element = $this->getElement();
        $view = $element->getView();
        if ( null === $view ) {
            return $content;
        }

        $label = $element->getLabel();

        $markup = sprintf(
                $this->_format, $view->translate( $label )
        );
        return $markup;
    }

}